import './bootstrap';

import Alpine from 'alpinejs'

window.Alpine = Alpine



import Sortable from 'sortablejs';

document.addEventListener('livewire:load', function() {
    console.log('livewire loaded'); // Your JS here.
});

Alpine.data('sortableList', () =>
    ({
        init () {

            var el = document.querySelector('[data-sortable]');
            var sortable = Sortable.create(el, {
                sort: true,
                handle: ".handle",
                onEnd: (evt) => {
                    const ids = Array.from(el.querySelectorAll('[data-id]')).map(el => el.dataset.id);
                    console.log(ids);

                    this.$wire.saveSort(ids);
                }

            });

            console.log(sortable);
        }
    })
)

Alpine.start()

