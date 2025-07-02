<?php
session_start();

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


// Holiday function
function getPhilippineHolidays($year) {
    $fixedHolidays = [
        //Regular Holidays
        $year . '-01-01' => 'New Year\'s Day',
        $year . '-04-09' => 'Araw ng Kagitingan',
        $year . '-04-17' => 'Maundy Thursday',
        $year . '-04-18' => 'Good Friday',
        $year . '-05-01' => 'Labor Day',
        $year . '-06-12' => 'Independence Day',
        $year . '-08-25' => 'National Heroes\' Day',
        $year . '-11-30' => 'Bonifacio Day',
        $year . '-12-25' => 'Christmas Day',
        $year . '-12-30' => 'Rizal Day',
        //Special (Non-Working) Holidays
        $year . '-01-29' => 'Chinese New Year',
        $year . '-04-19' => 'Black Saturday',
        $year . '-08-19' => 'Quezon City Day',
        $year . '-08-21' => 'Ninoy Aquino Day',
        $year . '-02-25' => 'EDSA Revolution Anniversary',
        $year . '-10-31' => 'All Saints\' Day Eve',
        $year . '-11-01' => 'All Saints\' Day',
        $year . '-12-08' => 'Feast of the Immaculate Conception',
        $year . '-12-24' => 'Christmas Eve',
        $year . '-12-31' => 'Last Day of the Year',
        //Special (Working) Holidays
        $year . '-02-25' => 'EDSA People Power Revolution Anniversary'

    ];
    
    $easter = new DateTime('@' . easter_date($year));
    $easter->modify('+1 day');
    $goodFriday = clone $easter;
    $goodFriday->modify('-2 days');
    
    $movableHolidays = [
        $goodFriday->format('Y-m-d') => 'Good Friday',
        $easter->format('Y-m-d') => 'Easter Sunday'
    ];
    
    return array_merge($fixedHolidays, $movableHolidays);
}

$currentYear = date('Y');
$holidays = getPhilippineHolidays($currentYear);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Calendar</title>
    <style>
        body {
            margin: 32px 12px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
            Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background: #f5f7fa;
            color: #222;
        }
        
        .calendar-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .calendar-header {
            padding: 16px;
            background: #4f46e5;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .calendar-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .header-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .calendar-grid {
            display: flex;
        }
        
        .employee-column {
            width: 200px;
            background-color: #f8fafc;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }
        
        .employee-header {
            height: 39px;
            display: flex;
            align-items: flex-end;
            padding: 12px 16px;
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }
        
        .employee-card {
            padding: 16px;
            /* border-bottom: 1px solid #e2e8f0; */
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-sizing: border-box;
        }
        
        .employee-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        
        .employee-position {
            color: #64748b;
            font-size: 0.7rem;
        }
        
        .calendar-days {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        
        .days-header {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .day-header {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            background: #f1f5f9;
            min-width: 0;
        }
        
        .calendar-rows {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        .calendar-row {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
            min-height: 100px;
            height: 100px; /* Fixed height for rows */
        }
        
        .calendar-cell {
            flex: 1;
            padding: 6px;
            border-right: 1px solid #e2e8f0;
            position: relative;
            min-width: 0;
            min-height: 100px;
            max-height: 100px; /* Fixed height */
            box-sizing: border-box;
            overflow-y: auto; /* Enable vertical scrolling */
        }
        
        .event {
            background: #4f46e5;
            color: white;
            padding: 4px 6px; /* Reduced padding */
            border-radius: 4px;
            margin-bottom: 2px; /* Reduced margin */
            font-size: 0.75rem; /* Smaller font */
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .event-title {
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .event-time {
            font-size: 0.65rem; /* Smaller font */
            opacity: 0.9;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal {
            background: #fff;
            border-radius: 16px;
            max-width: 500px;
            width: 90%;
            padding: 24px 32px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.25);
        }
        
        .modal h2 {
            margin: 0 0 20px 0;
            font-weight: 700;
            font-size: 1.4rem;
            color: #111;
        }
        
        .modal label {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 4px;
            display: block;
        }
        
        .modal input[type="text"],
        .modal textarea,
        .modal input[type="date"],
        .modal input[type="time"] {
            width: 100%;
            padding: 8px 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            transition: border-color 0.3s ease;
            font-family: inherit;
            margin-bottom: 16px;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
        
        .btn {
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #4f46e5;
            color: white;
        }
        
        .btn-primary:hover {
            background: #4338ca;
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #111;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .current-date {
            background: #f0fdf4;
        }
        
        .empty-cell {
            color: #94a3b8;
        font-style: italic;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        height: calc(100px - 12px);
        /* Add these properties */
        pointer-events: none; /* Allow clicks to pass through */
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        }

        .empty-cell.holiday-text {
            color: #d32f2f;
            font-weight: 600;
        }
        
        .week-display {
            font-weight: 600;
            margin: 0 15px;
            min-width: 180px;
            text-align: center;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #home-btn {
            margin-right: 10px; /* Add some spacing between buttons */
            text-decoration: none; /* Remove underline from link */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .event-creator {
            font-size: 0.55rem; /* Smaller font */
            opacity: 0.8;
            margin-top: 1px;
            font-style: italic;
      }
      
      .sunday-cell {
    background-color: #4d5656 !important;
    position: relative;
}

.sunday-cell::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background-color: #adb5bd;
}

.holiday-cell {
            background-color: #ffebee !important;
            position: relative;
        }
        
        .holiday-cell::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: #f44336;
        }
        
        .holiday-tooltip {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(244, 67, 54, 0.9);
            color: white;
            padding: 2px 4px;
            font-size: 0.7rem;
            text-align: center;
            border-radius: 0 0 4px 4px;
            z-index: 1;
        }

    </style>
</head>
<body>

    <div class="calendar-wrapper">
    <div class="calendar-header">
    <h2>IT SCHEDULE CALENDAR</h2>
    <a href="adminpanel.php" class="btn btn-secondary" id="home-btn">Home</a>
    <input type="hidden" id="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" readonly>
    <div class="header-nav">    
        <!-- Home Button -->

        
        <button id="prev-week" class="btn btn-secondary">Previous</button>
        <div class="week-display" id="week-display"></div>
        <button id="next-week" class="btn btn-secondary">Next</button>
    </div>
</div>
        
        <div class="calendar-grid">
            <div class="employee-column">
                <div class="employee-header">Employees</div>
                <!-- Employee list will be populated by JavaScript -->
            </div>
            
            <div class="calendar-days">
                <div class="days-header">
                    <!-- Day headers will be populated by JavaScript -->
                </div>
                
                <div class="calendar-rows">
                    <!-- Calendar rows will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for adding/editing events -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <h2 id="modalTitle">Add Event</h2>
            <form id="eventForm">
                <label for="eventTitle">Title</label>
                <input type="text" id="eventTitle" name="title" required placeholder="Event title">
                
                <label for="eventDate">Date</label>
                <input type="date" id="eventDate" name="date" required>
                
                <label for="eventTime">Time</label>
                <input type="time" id="eventTime" name="time" required>
                
                <label for="eventNote">Notes</label>
                <textarea id="eventNote" name="note" placeholder="Additional notes (optional)"></textarea>
                
                <div class="modal-buttons">
                    <button type="button" class="btn btn-danger" id="deleteBtn" style="display: none;">Delete</button>
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <span id="saveBtnText">Save Event</span>
                        <span id="saveBtnSpinner" class="loading" style="display: none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // API endpoints
            const API_BASE = 'api/';
            const EMPLOYEES_ENDPOINT = API_BASE + 'employees.php';
            const EVENTS_ENDPOINT = API_BASE + 'events.php';
            
            let holidays = <?php echo json_encode($holidays); ?>;
            // Current displayed week
            let currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            // Adjust to start of week (Monday)
            const dayOfWeek = currentDate.getDay();
            const diff = currentDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            currentDate.setDate(diff);
            
            // DOM elements
            const employeeColumn = document.querySelector('.employee-column');
            const daysHeader = document.querySelector('.days-header');
            const calendarRows = document.querySelector('.calendar-rows');
            const prevWeekBtn = document.getElementById('prev-week');
            const nextWeekBtn = document.getElementById('next-week');
            const weekDisplay = document.getElementById('week-display');
            const modalOverlay = document.getElementById('modalOverlay');
            const modalTitle = document.getElementById('modalTitle');
            const eventForm = document.getElementById('eventForm');
            const cancelBtn = document.getElementById('cancelBtn');
            const deleteBtn = document.getElementById('deleteBtn');
            const saveBtn = document.getElementById('saveBtn');
            const saveBtnText = document.getElementById('saveBtnText');
            const saveBtnSpinner = document.getElementById('saveBtnSpinner');
            
            // Form elements
            const eventTitleInput = document.getElementById('eventTitle');
            const eventDateInput = document.getElementById('eventDate');
            const eventTimeInput = document.getElementById('eventTime');
            const eventNoteInput = document.getElementById('eventNote');
            
            // State variables
            let selectedCell = null;
            let editingEvent = null;
            let employees = [];
            let events = [];
            
            // Initialize the calendar
            function initCalendar() {
                fetchEmployees().then(() => {
                    updateWeekDisplay();
                    renderWeekHeader();
                    fetchEvents().then(renderCalendarGrid);
                });
            }
            
            // Fetch employees from database
            async function fetchEmployees() {
                try {
                    const response = await fetch(EMPLOYEES_ENDPOINT);
                    if (!response.ok) throw new Error('Failed to fetch employees');
                    employees = await response.json();
                    renderEmployees();
                } catch (error) {
                    console.error('Error fetching employees:', error);
                    alert('Failed to load employees. Please try again.');
                }
            }
            
            // Fetch events from database
            async function fetchEvents() {
        try {
            const startDate = getFormattedDate(currentDate);
            const endDateObj = new Date(currentDate);
            endDateObj.setDate(currentDate.getDate() + 6);
            const endDate = getFormattedDate(endDateObj);
            
            const response = await fetch(`${EVENTS_ENDPOINT}?start=${startDate}&end=${endDate}`);
            if (!response.ok) throw new Error('Failed to fetch events');
            events = await response.json();
        } catch (error) {
            console.error('Error fetching events:', error);
            alert('Failed to load events. Please try again.');
        }
    }
            
            // Render employee list
            function renderEmployees() {
    // Clear existing employees (except the header)
    while (employeeColumn.children.length > 1) {
        employeeColumn.removeChild(employeeColumn.lastChild);
    }

    // Create a document fragment for better performance
    const fragment = document.createDocumentFragment();
    
    employees.forEach(employee => {
        const employeeCard = document.createElement('div');
        employeeCard.className = 'employee-card';
        employeeCard.dataset.employeeId = employee.userId;
        
        employeeCard.innerHTML = `
            <div class="employee-name">${employee.name}</div>
            
            <input type="hidden" class="employee-id" value="${employee.userId}">
        `;
        
        fragment.appendChild(employeeCard);
    });
    
    employeeColumn.appendChild(fragment);
}
            
            // Update the week display text
            function updateWeekDisplay() {
                const startOfWeek = new Date(currentDate);
                const endOfWeek = new Date(currentDate);
                endOfWeek.setDate(endOfWeek.getDate() + 6);
                
                const options = { month: 'short', day: 'numeric' };
                const startStr = startOfWeek.toLocaleDateString('en-US', options);
                const endStr = endOfWeek.toLocaleDateString('en-US', options);
                
                weekDisplay.textContent = `${startStr} - ${endStr}`;
            }
            
            // Render the week day headers
            function renderWeekHeader() {
                daysHeader.innerHTML = '';
                
                for (let i = 0; i < 7; i++) {
                    const day = new Date(currentDate);
                    day.setDate(currentDate.getDate() + i);
                    
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'day-header';

                    if (day.getDay() === 0) {
                        dayHeader.classList.add('sunday-cell');
                    }
                                
                    const dayName = day.toLocaleDateString('en-US', { weekday: 'short' });
                    const dayNumber = day.getDate();
                    
                    dayHeader.innerHTML = `
                        <div>${dayName}</div>
                        <div>${dayNumber}</div>
                    `;
                    
                    // Highlight current day
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (day.getDate() === today.getDate() && 
                        day.getMonth() === today.getMonth() && 
                        day.getFullYear() === today.getFullYear()) {
                        dayHeader.style.backgroundColor = '#e0f2fe';
                    }
                    
                    daysHeader.appendChild(dayHeader);
                }
            }
            
            // Render the calendar grid
            function renderCalendarGrid() {
    calendarRows.innerHTML = '';
    
    employees.forEach(employee => {
        const row = document.createElement('div');
        row.className = 'calendar-row';
        row.dataset.employeeId = employee.userId;
        
        for (let i = 0; i < 7; i++) {
            const day = new Date(currentDate);
            day.setDate(currentDate.getDate() + i);
            const dateStr = getFormattedDate(day);
            
            const cell = document.createElement('div');
            cell.className = 'calendar-cell';
            
            // Check if this is Sunday
            if (day.getDay() === 0) {
                cell.classList.add('sunday-cell');
            }
            
            // Check if this is a holiday
            const isHoliday = holidays[dateStr];
            if (isHoliday) {
                cell.classList.add('holiday-cell');
                
                const holidayTooltip = document.createElement('div');
                holidayTooltip.className = 'holiday-tooltip';
                holidayTooltip.textContent = holidays[dateStr];
                cell.appendChild(holidayTooltip);
            }
            
            // Find events for this employee and day
            const dayEvents = events.filter(event => 
                event.employee_id == employee.userId && event.date === dateStr
            );
            
            if (dayEvents.length > 0) {
                dayEvents.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = 'event';
                    eventElement.innerHTML = `
                        <div class="event-title">${event.title}</div>
                        <div class="event-time">${event.note || ''}</div>
                        ${event.employee_id != event.created_by ? 
                            `<div class="event-creator">Created by: <br> ${getEmployeeName(event.created_by)}</div>` : 
                            ''}
                    `;
                    
                    eventElement.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const userId = parseInt(document.getElementById('user_id').value);
                        const isRestrictedUser = [282, 288].includes(userId);
                        
                        if (isRestrictedUser && event.employee_id !== userId) {
                            alert('You can only edit your own events.');
                            return;
                        }
                        
                        editingEvent = {
                            id: event.id,
                            employee_id: event.employee_id,
                            title: event.title,
                            date: event.date,
                            time: event.time,
                            note: event.note || ''
                        };
                        openModal('Edit Event', event.date, editingEvent);
                    });
                    
                    cell.appendChild(eventElement);
                });
            } else {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'empty-cell';
                
                if (isHoliday) {
                    emptyCell.textContent = 'Holiday';
                    emptyCell.classList.add('holiday-text');
                } else {
                    emptyCell.textContent = 'No events';
                }
                
                cell.appendChild(emptyCell);
            }
            
            // Add click handler to the cell itself
            cell.addEventListener('click', function() {
                const userId = parseInt(document.getElementById('user_id').value);
                const isRestrictedUser = [282, 288].includes(userId);
                
                // Get the employee ID from the row
                const employeeId = parseInt(this.closest('.calendar-row').dataset.employeeId);
                
                if (isRestrictedUser && employeeId !== userId) {
                    alert('You can only create events for yourself.');
                    return;
                }
                
                selectedCell = { employeeId: employeeId, date: dateStr };
                editingEvent = null;
                openModal('Add Event', dateStr);
            });
            
            row.appendChild(cell);
        }
        
        calendarRows.appendChild(row);
    });
}
            
            // Open the modal
            function openModal(title, date, event = null) {
    const userId = parseInt(document.getElementById('user_id').value);
    const isRestrictedUser = [282, 288].includes(userId);
    
    // For restricted users editing existing events
    if (event && isRestrictedUser && event.employee_id !== userId) {
        alert('You can only edit your own events.');
        return;
    }
    
    // For restricted users creating new events
    if (!event && isRestrictedUser && selectedCell.employeeId !== userId) {
        alert('You can only add events for yourself.');
        return;
    }

    // Add creator information if this is an existing event
    if (event) {
        if (event.created_by && event.created_by !== event.employee_id) {
            const creatorName = getEmployeeName(event.created_by);
            title = `${title} (Created by ${creatorName})`;
        }
    }

    // Update modal for holidays
    if (holidays[date] && !event) {
        modalTitle.textContent = `Holiday: ${holidays[date]}`;
        // Don't pre-fill the title or make it read-only
        eventTitleInput.value = '';
    } else {
        modalTitle.textContent = title;
        eventTitleInput.value = event ? event.title : '';
    }
    
    // Always allow editing the title
    eventTitleInput.readOnly = false;
    
    eventDateInput.value = date;
    eventTimeInput.value = event ? event.time : '09:00';
    eventNoteInput.value = event ? event.note : '';
    
    // Only show delete button for own events (for restricted users)
    deleteBtn.style.display = (event && (!isRestrictedUser || event.employee_id === userId)) 
        ? 'block' 
        : 'none';
    
    modalOverlay.classList.add('active');
}
            
            // Close the modal
            function closeModal() {
                modalOverlay.classList.remove('active');
                selectedCell = null;
                editingEvent = null;
            }
            
            // Save event to database
            async function saveEvent(eventData) {
    try {
        showLoading(true);
        
        const url = eventData.id ? `${EVENTS_ENDPOINT}?id=${eventData.id}` : EVENTS_ENDPOINT;
        const method = eventData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(eventData)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to save event');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error saving event:', error);
        throw error;
    } finally {
        showLoading(false);
    }
}
            
            // Delete event from database
            async function deleteEvent(eventId) {
                try {
                    showLoading(true);
                    
                    const response = await fetch(`${EVENTS_ENDPOINT}?id=${eventId}`, {
                        method: 'DELETE'
                    });
                    
                    if (!response.ok) throw new Error('Failed to delete event');
                    
                    const result = await response.json();
                    return result;
                } catch (error) {
                    console.error('Error deleting event:', error);
                    throw error;
                } finally {
                    showLoading(false);
                }
            }
            
            // Show/hide loading state
            function showLoading(loading) {
                if (loading) {
                    saveBtn.disabled = true;
                    saveBtnText.style.display = 'none';
                    saveBtnSpinner.style.display = 'inline-block';
                } else {
                    saveBtn.disabled = false;
                    saveBtnText.style.display = 'inline';
                    saveBtnSpinner.style.display = 'none';
                }
            }
            
            function getEmployeeName(employeeId) {
    const employee = employees.find(emp => emp.userId == employeeId);
    return employee ? employee.name : 'Unknown';
}


            // Helper function to format date as YYYY-MM-DD
            function getFormattedDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            
            // Helper function to add days to a date
            function addDays(date, days) {
                const result = new Date(date);
                result.setDate(result.getDate() + days);
                return result;
            }
            
            // Event listeners
            prevWeekBtn.addEventListener('click', function() {
                currentDate.setDate(currentDate.getDate() - 7);
                updateWeekDisplay();
                renderWeekHeader();
                fetchEvents().then(renderCalendarGrid);
            });
            
            nextWeekBtn.addEventListener('click', function() {
                currentDate.setDate(currentDate.getDate() + 7);
                updateWeekDisplay();
                renderWeekHeader();
                fetchEvents().then(renderCalendarGrid);
            });
            
            cancelBtn.addEventListener('click', closeModal);
            
            deleteBtn.addEventListener('click', async function() {
    if (!editingEvent || !confirm('Are you sure you want to delete this event?')) return;
    
    const userId = parseInt(document.getElementById('user_id').value);
    const isRestrictedUser = [282, 288].includes(userId);
    
    if (isRestrictedUser && editingEvent.employee_id !== userId) {
        alert('You can only delete your own events.');
        return;
    }
    
    try {
        await deleteEvent(editingEvent.id);
        closeModal();
        fetchEvents().then(renderCalendarGrid);
    } catch (error) {
        alert('Failed to delete event. Please try again.');
    }
});
            
eventForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const userId = parseInt(document.getElementById('user_id').value);
    const isRestrictedUser = [282, 288].includes(userId);
    
    // For restricted users, verify they're not trying to modify someone else's event
    if (isRestrictedUser) {
        if (editingEvent && editingEvent.employee_id !== userId) {
            alert('You can only modify your own events.');
            return;
        }
        if (!editingEvent && selectedCell.employeeId !== userId) {
            alert('You can only add events for yourself.');
            return;
        }
    }
    
    const title = eventTitleInput.value.trim();
    const date = eventDateInput.value;
    const time = eventTimeInput.value;
    const note = eventNoteInput.value.trim();
    
    if (!title) {
        alert('Please enter a title for the event.');
        return;
    }
    
    const eventData = {
        employee_id: editingEvent ? editingEvent.employee_id : selectedCell.employeeId,
        title,
        date,
        time,
        note,
        created_by: parseInt(document.getElementById('user_id').value)
    };
    
    try {
        if (editingEvent) {
            eventData.id = editingEvent.id;
            await saveEvent(eventData);
        } else {
            await saveEvent(eventData);
        }
        
        closeModal();
        await fetchEvents();
        renderCalendarGrid();
    } catch (error) {
        alert('Failed to save event. Please try again.');
        console.error('Error:', error);
    }
});
            
            // Initialize the calendar
            initCalendar();
        });
    </script>
</body>
</html>