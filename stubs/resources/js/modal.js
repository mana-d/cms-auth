"use strict";

var hide;

const toggleModal = (modalId, show = true) => {
    const modalEl = document.getElementById(modalId);

    if (show) {
        document.body.classList.add('overflow-hidden');

        var backdropEl = document.createElement('div');
        backdropEl.setAttribute('mana-modal-backdrop', '');
        backdropEl.classList.add('modal-backdrop');
        document.querySelector('body').append(backdropEl);

        modalEl.classList.add('show');
        modalEl.setAttribute('aria-modal', 'true');
        modalEl.setAttribute('role', 'dialog');
        modalEl.removeAttribute('aria-hidden');

        hide = function (ev) {
            _handleOutsideClick(ev.target, modalId, modalEl, backdropEl);
        }

        modalEl.addEventListener('click', hide, true);        
    } else {
        modalEl.setAttribute('aria-hidden', 'true');
        modalEl.removeAttribute('aria-modal');
        modalEl.removeAttribute('role');

        modalEl.removeEventListener('click', hide, true);

        modalEl.classList.remove('show')
        document.querySelector('[mana-modal-backdrop]').remove();
        document.body.classList.remove('overflow-hidden');
    }
};

function _handleOutsideClick (target, modalId, modalEl, backdropEl) {
    if (target === modalEl || (target === backdropEl)) {
        toggleModal(modalId, false);
    }
}

window.toggleModal = toggleModal;
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-mana-modal-toggle]').forEach(function (modalToggleEl) {
        var modalId = modalToggleEl.getAttribute('data-mana-modal-toggle');
        var modalEl = document.getElementById(modalId);

        if (modalEl) {
            if (!modalEl.hasAttribute('aria-hidden') && !modalEl.hasAttribute('aria-modal')) {
                modalEl.setAttribute('aria-hidden', 'true');
            }

            modalToggleEl.addEventListener('click', function () {
                toggleModal(modalId, modalEl.hasAttribute('aria-hidden', 'true'));
            });
        }
    });
});