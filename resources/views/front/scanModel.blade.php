<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title name text-uppercase" style="text-align: center;"><span id="domain_name"></span></h4>
        </div>
        <div class="modal-body">
          <p>You may need to wait a few minutes for your domains Cybersecurity Rating. The scanners could working overtime to service multiple requests.</p>
          <p id="scanProcessTxt"></p>
          <p id="scanCompleteTxt" style="display: none;">Domain scanning completed.</p>
          <div class="statusbar">
            <span class="cat1">E-mail</span>
            <span class="cat2">Website</span>
            <span class="cat3">Compromised</span>
            <span class="cat4">Vulnerability</span>
            <span class="cat5">Data-Privacy</span>
            <!-- <span class="cat6">Breached Account</span> -->
          </div>
          <div class="preloader" id="preloader">
              <div class="loading-process">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="close btn btn-default" onclick = "closemodel()" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>