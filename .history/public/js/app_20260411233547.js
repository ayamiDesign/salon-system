document.addEventListener('DOMContentLoaded', function () {

    // =============================
    // メニュー
    // =============================
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.getElementById('mobileGlobalMenu');
    const mobileSortModeButton = document.getElementById('mobileSortModeButton');

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            const nextExpanded = !expanded;

            toggle.setAttribute('aria-expanded', nextExpanded ? 'true' : 'false');
            menu.hidden = !nextExpanded;
            menu.classList.toggle('is-open', nextExpanded);
        });
    }

    // =============================
    // 共通設定
    // =============================
    const sortContainer = document.querySelector('[data-sort-container]');
    const deleteBaseUrl = sortContainer?.dataset.deleteBaseUrl || '';
    const sortEndpoint = sortContainer?.dataset.sortEndpoint || '';

    // =============================
    // 削除モーダル
    // =============================
    const modal = document.getElementById('deleteModal');
    const closeButton = document.getElementById('deleteModalClose');
    const deleteForm = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');
    const openButtons = document.querySelectorAll('.js-delete-open');

    openButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (!modal || !deleteForm || !deleteItemName) return;

            const itemId = this.dataset.id;
            const itemName = this.dataset.name || '';

            deleteItemName.textContent = itemName;

            if (deleteBaseUrl) {
                deleteForm.action = `${deleteBaseUrl}/${itemId}`;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    closeButton?.addEventListener('click', function () {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
    });

    modal?.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    // =============================
    // 並び替えモードUI
    // =============================
    const sortModeButton = document.getElementById('sortModeButton');
    const saveSortButton = document.getElementById('saveSortButton');
    const cancelSortButton = document.getElementById('cancelSortButton');
    const sortGuide = document.getElementById('sortGuide');
    const normalActions = document.querySelectorAll('.js-normal-actions');
    const sortHandles = document.querySelectorAll('.js-sort-handle');
    const sortableRows = document.querySelectorAll('.js-sort-row');

    let isSortMode = false;
    let draggedItem = null;

    function enterSortMode() {
        if (!sortableRows.length) return;

        isSortMode = true;

        sortModeButton?.classList.add('hidden');
        saveSortButton?.classList.remove('hidden');
        cancelSortButton?.classList.remove('hidden');
        sortGuide?.classList.remove('hidden');

        sortHandles.forEach(el => el.classList.remove('hidden'));
        normalActions.forEach(el => el.classList.add('hidden'));

        sortableRows.forEach(row => {
            row.setAttribute('draggable', 'true');
            row.classList.add('cursor-move');
        });
    }

    function leaveSortMode(reset = false) {
        isSortMode = false;

        sortModeButton?.classList.remove('hidden');
        saveSortButton?.classList.add('hidden');
        cancelSortButton?.classList.add('hidden');
        sortGuide?.classList.add('hidden');

        sortHandles.forEach(el => el.classList.add('hidden'));
        normalActions.forEach(el => el.classList.remove('hidden'));

        sortableRows.forEach(row => {
            row.removeAttribute('draggable');
            row.classList.remove('cursor-move', 'opacity-60', 'ring-2', 'ring-accent-100');
        });

        if (reset) {
            window.location.reload();
            return;
        }

        updateSortOrderLabels();
    }

    // function updateSortOrderLabels() {
    //     const pcRows = document.querySelectorAll('#pc-sortable-body .js-sort-row');

    //     pcRows.forEach((row, index) => {
    //         const order = index + 1;
    //         row.dataset.order = order;

    //         const id = row.dataset.id;

    //         const pcLabel = row.querySelector('.js-sort-order-label');
    //         if (pcLabel) {
    //             pcLabel.textContent = order;
    //         }

    //         const spCard = document.querySelector(`.js-sp-card[data-id="${id}"]`);
    //         if (spCard) {
    //             spCard.dataset.order = order;

    //             const spLabel = spCard.querySelector('.js-sp-sort-order-label');
    //             if (spLabel) {
    //                 spLabel.textContent = order;
    //             }
    //         }
    //     });
    // }

    sortModeButton?.addEventListener('click', enterSortMode);

    cancelSortButton?.addEventListener('click', function () {
        leaveSortMode(true);
    });

    saveSortButton?.addEventListener('click', async function () {
        if (!sortEndpoint) {
            alert('並び替え保存先が設定されていません');
            return;
        }

        const orderedIds = Array.from(document.querySelectorAll('#pc-sortable-body .js-sort-row'))
            .map(row => row.dataset.id);

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        saveSortButton.disabled = true;
        saveSortButton.textContent = '保存中...';

        try {
            const response = await fetch(sortEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ids: orderedIds
                })
            });

            if (!response.ok) {
                throw new Error('保存失敗');
            }

            const data = await response.json();
            alert(data.message || '表示順を保存しました');
            window.location.reload();

        } catch (error) {
            alert('表示順の保存に失敗しました');
            console.error(error);
        } finally {
            saveSortButton.disabled = false;
            saveSortButton.textContent = '保存する';
        }
    });

    sortableRows.forEach(row => {
        row.addEventListener('dragstart', function () {
            if (!isSortMode) return;
            draggedItem = this;
            this.classList.add('opacity-60');
        });

        row.addEventListener('dragend', function () {
            this.classList.remove('opacity-60');
            document.querySelectorAll('.js-sort-row').forEach(r => {
                r.classList.remove('ring-2', 'ring-accent-100');
            });
        });

        row.addEventListener('dragover', function (e) {
            if (!isSortMode) return;
            e.preventDefault();
            this.classList.add('ring-2', 'ring-accent-100');
        });

        row.addEventListener('dragleave', function () {
            this.classList.remove('ring-2', 'ring-accent-100');
        });

        row.addEventListener('drop', function (e) {
            if (!isSortMode) return;
            e.preventDefault();
            this.classList.remove('ring-2', 'ring-accent-100');

            if (!draggedItem || draggedItem === this) return;

            const tbody = this.parentNode;
            const rows = Array.from(tbody.querySelectorAll('.js-sort-row'));
            const draggedIndex = rows.indexOf(draggedItem);
            const targetIndex = rows.indexOf(this);

            if (draggedIndex < targetIndex) {
                tbody.insertBefore(draggedItem, this.nextSibling);
            } else {
                tbody.insertBefore(draggedItem, this);
            }

            updateSortOrderLabels();
        });
    });

    updateSortOrderLabels();
});