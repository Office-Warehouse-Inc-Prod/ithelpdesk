<?php
include 'userheader.php';
require_once 'db.php';

$departments = [];

try {
    $statement = $connection->prepare("
        SELECT dept_desc, contactNumber, dept_email
        FROM tbl_dept
        WHERE (dept_desc IS NOT NULL AND dept_desc <> '')
          AND (
              (contactNumber IS NOT NULL AND contactNumber <> '')
              OR (dept_email IS NOT NULL AND dept_email <> '')
          )
        ORDER BY dept_desc ASC
    ");
    $statement->execute();
    $departments = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    $departments = [];
}

$departmentCount = count($departments);
$withPhoneCount = count(array_filter($departments, function ($department) {
    return trim((string) ($department['contactNumber'] ?? '')) !== '';
}));
$withEmailCount = count(array_filter($departments, function ($department) {
    return trim((string) ($department['dept_email'] ?? '')) !== '';
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OWI Helpdesk Contact Directory</title>
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/4bootstrap.min.css" />
  <script src="../js/moment.min.js"></script>
  <script src="https://use.fontawesome.com/f942a1dc17.js"></script>

<style>
  :root {
    --primary-color: #E1AD01;
    --primary-color-deep: #bb8f00;
    --primary-soft: #fff6d7;
    --navy: #213456;
    --navy-deep: #121c31;
    --navy-soft: #e8eef8;
    --text-main: #24324a;
    --text-muted: #64748b;
    --line-soft: rgba(33, 52, 86, 0.12);
    --card-shadow: 0 18px 45px rgba(18, 28, 49, 0.08);
  }

  .navbar,
  header,
  .topbar,
  .navbar-default {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    min-height: 60px !important;
  }

  .navbar-brand {
    font-size: 18px !important;
    font-weight: 700;
  }

  .navbar img {
    max-height: 42px !important;
  }

  .navbar,
  header,
  .topbar,
  .navbar-default {
    background: #121C31 !important;
    border-color: rgba(255,255,255,.12) !important;
  }

  .navbar .navbar-brand,
  .navbar .navbar-brand span,
  .navbar a,
  .navbar-nav > li > a {
    color: #ffffff !important;
  }

  .navbar a:hover,
  .navbar-nav > li > a:hover,
  .navbar a:focus,
  .navbar-nav > li > a:focus {
    color: #E5E7EB !important;
    opacity: .95;
  }

  .navbar .dropdown-menu {
    background: #121C31 !important;
    border: 1px solid rgba(255,255,255,.12) !important;
  }

  .navbar .dropdown-menu a {
    color: #ffffff !important;
  }

  .navbar .dropdown-menu a:hover {
    background: rgba(255,255,255,.08) !important;
  }

  .navbar i,
  .navbar .fa,
  .navbar .fas {
    color: #ffffff !important;
  }

  body {
    background:
      radial-gradient(circle at top right, rgba(225, 173, 1, 0.16), transparent 18%),
      linear-gradient(180deg, #f4f6fb 0%, #eef2f8 100%);
    color: var(--text-main);
    font-family: 'Raleway', sans-serif;
  }

  .contact-shell {
    padding: 26px 16px 40px;
  }

  .contact-hero,
  .contact-panel,
  .schedule-panel {
    border: 1px solid rgba(33, 52, 86, 0.08);
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.96);
    box-shadow: var(--card-shadow);
  }

  .contact-hero {
    overflow: hidden;
    position: relative;
    margin-bottom: 18px;
  }

  .contact-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy) 60%, #2d4b77 100%);
  }

  .contact-hero::after {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    background: rgba(225, 173, 1, 0.16);
    top: -130px;
    right: -70px;
  }

  .hero-content {
    position: relative;
    z-index: 2;
    padding: 28px;
    color: #fff;
  }

  .hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1.1px;
    text-transform: uppercase;
  }

  .hero-title {
    margin: 16px 0 10px;
    font-size: 34px;
    font-weight: 800;
    line-height: 1.1;
  }

  .hero-copy {
    max-width: 720px;
    color: rgba(255, 255, 255, 0.84);
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 0;
  }

  .stat-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-top: 24px;
  }

  .stat-card {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 18px;
    padding: 16px 18px;
    backdrop-filter: blur(4px);
  }

  .stat-label {
    display: block;
    color: rgba(255, 255, 255, 0.74);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    font-weight: 700;
  }

  .stat-value {
    display: block;
    margin-top: 8px;
    font-size: 27px;
    font-weight: 800;
    color: #fff;
  }

  .contact-panel {
    padding: 22px;
  }

  .panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 18px;
  }

  .panel-title {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: var(--navy-deep);
  }

  .panel-subtitle {
    margin: 6px 0 0;
    color: var(--text-muted);
    font-size: 13px;
  }

  .table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 18px;
  }

  .toolbar-search {
    position: relative;
    width: 100%;
    max-width: 340px;
  }

  .toolbar-search i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: var(--text-muted);
  }

  .toolbar-search input {
    width: 100%;
    height: 48px;
    border-radius: 16px;
    border: 1px solid var(--line-soft);
    background: #f8fafc;
    padding: 0 16px 0 42px;
    color: var(--text-main);
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .toolbar-search input:focus {
    border-color: rgba(225, 173, 1, 0.65);
    box-shadow: 0 0 0 4px rgba(225, 173, 1, 0.12);
  }

  .source-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    background: var(--primary-soft);
    color: var(--primary-color-deep);
  }

  .table-wrap {
    overflow-x: auto;
    border: 1px solid rgba(33, 52, 86, 0.08);
    border-radius: 20px;
    background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
  }

  .contact-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
  }

  .contact-table thead th {
    padding: 16px 18px;
    background: #f6f8fc;
    color: var(--navy);
    font-size: 12px;
    letter-spacing: 0.9px;
    font-weight: 800;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(33, 52, 86, 0.08);
    white-space: nowrap;
  }

  .contact-table tbody td {
    padding: 18px;
    vertical-align: middle;
    border-bottom: 1px solid rgba(33, 52, 86, 0.08);
  }

  .contact-table tbody tr:last-child td {
    border-bottom: none;
  }

  .contact-table tbody tr:hover td {
    background: rgba(33, 52, 86, 0.025);
  }

  .dept-cell {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 240px;
  }

  .dept-avatar {
    width: 46px;
    height: 46px;
    border-radius: 15px;
    background: linear-gradient(145deg, var(--navy) 0%, #3a5a89 100%);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 16px;
    box-shadow: 0 10px 20px rgba(33, 52, 86, 0.18);
    flex: 0 0 46px;
  }

  .dept-name {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    color: var(--navy-deep);
  }

  .dept-meta {
    margin-top: 4px;
    font-size: 12px;
    color: var(--text-muted);
    letter-spacing: 0.3px;
  }

  .contact-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--navy-deep);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.2px;
    text-decoration: none !important;
    word-break: break-word;
  }

  .contact-link .icon-box {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-soft);
    color: var(--primary-color-deep);
    flex: 0 0 38px;
  }

  .contact-empty {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 88px;
    padding: 8px 14px;
    border-radius: 999px;
    background: #f8fafc;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 800;
  }

  .empty-state {
    padding: 36px 20px;
    text-align: center;
    color: var(--text-muted);
  }

  @media (max-width: 991.98px) {
    .stat-grid {
      grid-template-columns: 1fr;
    }

    .hero-title {
      font-size: 28px;
    }
  }

  @media (max-width: 767.98px) {
    .contact-shell {
      padding-top: 18px;
    }

    .hero-content,
    .contact-panel {
      padding: 18px;
    }

    .panel-head,
    .table-toolbar {
      align-items: flex-start;
    }

    .contact-table thead {
      display: none;
    }

    .contact-table,
    .contact-table tbody,
    .contact-table tr,
    .contact-table td {
      display: block;
      width: 100%;
    }

    .contact-table tbody tr {
      padding: 18px;
      border-bottom: 1px solid rgba(33, 52, 86, 0.08);
    }

    .contact-table tbody tr:last-child {
      border-bottom: none;
    }

    .contact-table tbody td {
      border: none;
      padding: 0 0 12px;
    }

    .contact-table tbody td:last-child {
      padding-bottom: 0;
    }
  }
</style>
</head>
<body>

<div class="container-fluid contact-shell">
  <div class="contact-hero">
    <div class="hero-content">
      <span class="hero-eyebrow"><i class="fas fa-address-book"></i> Contact Us</span>
      <h1 class="hero-title">Contact Directory</h1>
      <p class="hero-copy">
        Browse department contact details in one place.
      </p>

      <div class="stat-grid">
        <div class="stat-card">
          <span class="stat-label">Departments</span>
          <span class="stat-value"><?php echo $departmentCount; ?></span>
        </div>
        <div class="stat-card">
          <span class="stat-label">With Contact No.</span>
          <span class="stat-value"><?php echo $withPhoneCount; ?></span>
        </div>
        <div class="stat-card">
          <span class="stat-label">With Email</span>
          <span class="stat-value"><?php echo $withEmailCount; ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-3">
      <section class="contact-panel">
        <div class="panel-head">
          <div>
            <h2 class="panel-title">Department Directory</h2>
            <p class="panel-subtitle">Showing department name, contact number, and email address.</p>
          </div>
          <span class="source-pill"><i class="fas fa-database"></i> Live directory</span>
        </div>

        <div class="table-toolbar">
          <div class="toolbar-search">
            <i class="fas fa-search"></i>
            <input type="text" id="contactSearch" placeholder="Search department, number, or email">
          </div>
          <span class="panel-subtitle mb-0">Showing <strong id="visibleCount"><?php echo $departmentCount; ?></strong> department<?php echo $departmentCount === 1 ? '' : 's'; ?></span>
        </div>

        <div class="table-wrap">
          <table class="contact-table">
            <thead>
              <tr>
                <th>Department</th>
                <th>Contact Number</th>
                <th>Email Address</th>
              </tr>
            </thead>
            <tbody id="contactTableBody">
              <?php if (empty($departments)): ?>
                <tr>
                  <td colspan="3">
                    <div class="empty-state">No department contact details found.</div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($departments as $department): ?>
                  <?php
                    $deptDesc = trim((string) ($department['dept_desc'] ?? ''));
                    $contactNumber = trim((string) ($department['contactNumber'] ?? ''));
                    $deptEmail = trim((string) ($department['dept_email'] ?? ''));
                    $initials = '';
                    $parts = preg_split('/\s+/', $deptDesc);
                    foreach ($parts as $part) {
                        if ($part !== '') {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 2);
                    $telLink = preg_replace('/[^0-9+]/', '', $contactNumber);
                  ?>
                  <tr class="contact-row">
                    <td data-label="Department">
                      <div class="dept-cell">
                        <span class="dept-avatar"><?php echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></span>
                        <div>
                          <p class="dept-name"><?php echo htmlspecialchars($deptDesc, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                      </div>
                    </td>
                    <td data-label="Contact Number">
                      <?php if ($contactNumber !== ''): ?>
                        <a class="contact-link" href="tel:<?php echo htmlspecialchars($telLink, ENT_QUOTES, 'UTF-8'); ?>">
                          <span class="icon-box"><i class="fas fa-phone-alt"></i></span>
                          <span><?php echo htmlspecialchars($contactNumber, ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                      <?php else: ?>
                        <span class="contact-empty">No number</span>
                      <?php endif; ?>
                    </td>
                    <td data-label="Email Address">
                      <?php if ($deptEmail !== ''): ?>
                        <a class="contact-link" href="mailto:<?php echo htmlspecialchars($deptEmail, ENT_QUOTES, 'UTF-8'); ?>">
                          <span class="icon-box"><i class="fas fa-envelope"></i></span>
                          <span><?php echo htmlspecialchars($deptEmail, ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                      <?php else: ?>
                        <span class="contact-empty">No email</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
  (function () {
    var searchInput = document.getElementById('contactSearch');
    var tableBody = document.getElementById('contactTableBody');
    var visibleCount = document.getElementById('visibleCount');

    if (!searchInput || !tableBody || !visibleCount) {
      return;
    }

    var rows = Array.prototype.slice.call(tableBody.querySelectorAll('.contact-row'));

    function updateDirectoryFilter() {
      var keyword = searchInput.value.toLowerCase().trim();
      var count = 0;

      rows.forEach(function (row) {
        var text = row.textContent.toLowerCase();
        var isMatch = keyword === '' || text.indexOf(keyword) !== -1;
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) {
          count += 1;
        }
      });

      visibleCount.textContent = count;
    }

    searchInput.addEventListener('input', updateDirectoryFilter);
  })();
</script>

</body>
</html>
