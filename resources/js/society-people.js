// Society People Management
document.addEventListener('DOMContentLoaded', function () {
    // Modal controls
    const modals = {
        promoteModal: document.getElementById('promoteModal'),
        passPresidentModal: document.getElementById('passPresidentModal'),
        downgradeModal: document.getElementById('downgradeModal'),
        kickModal: document.getElementById('kickModal'),
        leaveModal: document.getElementById('leaveModal'),
    };

    const closeButtons = document.querySelectorAll('[id$="Close"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const modalId = this.id.replace('Close', 'Modal');
            if (modals[modalId]) {
                modals[modalId].classList.add('hidden');
            }
        });
    });

    // Close modal when clicking outside
    Object.values(modals).forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });

    // Open pass president modal
    const passPresidentBtns = document.querySelectorAll('[data-action="pass-president"]');
    passPresidentBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const memberId = this.dataset.memberId;
            const memberName = this.dataset.memberName;
            document.getElementById('passPresidentForm').action = `/society/${this.dataset.societyId}/member/${memberId}/pass-president`;
            document.getElementById('passPresidentMessage').textContent = `Pass president role to ${memberName}?`;
            modals.passPresidentModal.classList.remove('hidden');
        });
    });

    // Open promote modal
    const promoteBtns = document.querySelectorAll('[data-action="promote"]');
    promoteBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const memberId = this.dataset.memberId;
            const memberName = this.dataset.memberName;
            document.getElementById('promoteForm').action = `/society/${this.dataset.societyId}/member/${memberId}/promote`;
            document.getElementById('promoteMessage').textContent = `Promote ${memberName} to committee?`;
            modals.promoteModal.classList.remove('hidden');
        });
    });

    // Open downgrade modal
    const downgradeBtns = document.querySelectorAll('[data-action="downgrade"]');
    downgradeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const memberId = this.dataset.memberId;
            const memberName = this.dataset.memberName;
            document.getElementById('downgradeForm').action = `/society/${this.dataset.societyId}/member/${memberId}/downgrade`;
            document.getElementById('downgradeMessage').textContent = `Downgrade ${memberName} to member level?`;
            modals.downgradeModal.classList.remove('hidden');
        });
    });

    // Open kick modal
    const kickBtns = document.querySelectorAll('[data-action="kick"]');
    kickBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const memberId = this.dataset.memberId;
            const memberName = this.dataset.memberName;
            const role = this.dataset.memberRole;
            document.getElementById('kickForm').action = `/society/${this.dataset.societyId}/member/${memberId}/kick`;
            document.getElementById('kickMessage').textContent = `Remove ${memberName} (${role}) from the society?`;
            modals.kickModal.classList.remove('hidden');
        });
    });

    // Open leave modal
    const leaveBtns = document.querySelectorAll('[data-action="leave"]');
    leaveBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const memberName = this.dataset.memberName;
            const userRole = this.dataset.userRole;
            document.getElementById('leaveForm').action = `/society/${this.dataset.societyId}/member/leave`;
            document.getElementById('leaveMessage').textContent = `Leave this society? You will lose your ${userRole} position.`;
            modals.leaveModal.classList.remove('hidden');
        });
    });

    console.log('People management script loaded');
});
