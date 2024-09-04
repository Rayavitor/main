document.addEventListener('DOMContentLoaded', () => {
    const dialogOverlay = document.getElementById('dialog-overlay');
    const openDialogButton = document.getElementById('open-dialog');
    const closeDialogButton = document.getElementById('close-dialog');
    const yesButton = document.getElementById('yes-button');
    const noButton = document.getElementById('no-button');

    function showDialog() {
        dialogOverlay.classList.remove('hidden');
    }

    function hideDialog() {
        dialogOverlay.classList.add('hidden');
    }

    openDialogButton.addEventListener('click', showDialog);
    closeDialogButton.addEventListener('click', hideDialog);
    yesButton.addEventListener('click', () => {
        alert('You clicked Yes!');
        hideDialog();
    });
    noButton.addEventListener('click', () => {
        alert('You clicked No!');
        hideDialog();
    });
});