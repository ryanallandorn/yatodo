document.addEventListener('livewire:load', function () {
    Livewire.on('simpleEvent', event => {
        console.log(event.message);
    });
});