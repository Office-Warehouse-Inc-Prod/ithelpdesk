function getcomments(datacom, uid) {
  // var  uid =<?= $_SESSION['user_id'] ?>;
  document.getElementById("remarks_view").innerHTML = datacom.msgs
    .map(function (comments) {
      return ` 
    
    <div class="comment-widgets mt-3 mb-3  ">
          <div class="d-flex flex-row comment-row">
              <div class="p-2"><span class="round"><img src="../images/users/${
                comments.usrimg
              }" alt="user" width="50"></span></div>
              <div class="comment-text w-100 ">
                  <h5>${uid == comments.usrid ? "You" : comments.tech}</h5>
                  <div class="comment-footer"> <span class="cdate">Sent: ${
                    comments.dt
                  }</span>  </div>
                <p class="mt-4"><strong>${comments.desc}</strong></p>
                 <span class="cdate">Replied:</span> <time class="cdate timeago" datetime="${
                   comments.dt
                 }"></time>
                 
              </div>
          </div>  
   </div>
   <hr>
    `;
    })
    .join("");
  $("time.timeago").timeago();
}
function getinfo(tid, gettype, usrid) {
  $("#remarks_view").empty();
  $.post(
    "../users/fetch.php",
    { tickid: tid, operation: gettype },
    function (data) {
      // var obj = JSON.parse(data);${ (uid==comments.usrid) ? "time-right":"time-left"}
      getcomments(data, usrid);
    },
    "json"
  );
}
