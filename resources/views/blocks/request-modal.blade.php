<div id="request-modal" class="modal modal--status modal--notification fade scroller-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>New friend request!</h5>
                <button type="button" class="close close-friend-request-modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal__status-icon-box modal__status-icon-box--request"><i class="fas fa-user-friends"></i></div>
                <p><span class="request-sender"></span> sent you a friend request.</p>
                <input hidden name="friendship_id" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary request-answer" data-alias="approve">Approve</button>
                <button type="button" class="btn btn-secondary request-answer" data-alias="reject">Reject</button>
            </div>
        </div>
    </div>
</div>
