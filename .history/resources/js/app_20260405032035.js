import './bootstrap';

 document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------
    // 削除モーダル
    // -----------------------------
    const modal = document.getElementById('deleteModal');
    const closeButton = document.getElementById('deleteModalClose');
    const deleteForm = document.getElementById('deleteForm');
    const deleteCategoryName = document.getElementById('deleteCategoryName');
    const openButtons = document.querySelectorAll('.js-delete-open');

    // 各削除ボタンにクリックイベントをつける
    openButtons.forEach(button => {
        button.addEventListener('click', function () {

            // カテゴリID・カテゴリ名を取得
            const categoryId = this.dataset.id;
            const categoryName = this.dataset.name;

            deleteCategoryName.textContent = categoryName;

                // 削除フォームの送信先URLをセット
            deleteForm.action = `/categories/${categoryId}`;

            // モーダルを表示
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    // キャンセルボタンを押したらモーダルを閉じる
    closeButton?.addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    // モーダルの背景クリックでも閉じる
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    // -----------------------------
    // 並び替えモードUI
    // -----------------------------
    const sortModeButton = document.getElementById('sortModeButton');
    const saveSortButton = document.getElementById('saveSortButton');
    const cancelSortButton = document.getElementById('cancelSortButton');
    const sortGuide = document.getElementById('sortGuide');
    const normalActions = document.querySelectorAll('.js-normal-actions');
    const sortHandles = document.querySelectorAll('.js-sort-handle');
    const sortableRows = document.querySelectorAll('.js-sort-row');

    // 並び替え中かどうか
    let isSortMode = false;
    // 今ドラッグしている行
    let draggedItem = null;

    // 並び替えモードON
    function enterSortMode() {
        isSortMode = true;

        // ボタン表示切り替え
        sortModeButton.classList.add('hidden');
        saveSortButton.classList.remove('hidden');
        cancelSortButton.classList.remove('hidden');
        sortGuide.classList.remove('hidden');

        // ハンドル表示、通常ボタン非表示
        sortHandles.forEach(el => el.classList.remove('hidden'));
        normalActions.forEach(el => el.classList.add('hidden'));

        // ドラッグ可能にする
        sortableRows.forEach(row => {
            row.setAttribute('draggable', 'true');
            row.classList.add('cursor-move');
        });
    }

    // 並び替えモードOFF
    function leaveSortMode(reset = false) {
        isSortMode = false;

        // ボタン表示戻す
        sortModeButton.classList.remove('hidden');
        saveSortButton.classList.add('hidden');
        cancelSortButton.classList.add('hidden');
        sortGuide.classList.add('hidden');

        // ハンドル非表示、通常ボタン表示
        sortHandles.forEach(el => el.classList.add('hidden'));
        normalActions.forEach(el => el.classList.remove('hidden'));

        // ドラッグ無効化
        sortableRows.forEach(row => {
            row.removeAttribute('draggable');
            row.classList.remove('cursor-move', 'opacity-60', 'ring-2', 'ring-accent-100');
        });

        // キャンセル時はリロード
        if (reset) {
            window.location.reload();
        }

        // 表示順を更新
        updateSortOrderLabels();
    }

    // 表示順の数字を更新
    function updateSortOrderLabels() {
        const pcRows = document.querySelectorAll('#pc-sortable-body .js-sort-row');
        pcRows.forEach((row, index) => {
            const order = index + 1;
            // data-order更新
            row.dataset.order = order;

            const id = row.dataset.id;

            // 表示番号更新
            const pcLabel = row.querySelector('.js-sort-order-label');
            if (pcLabel) pcLabel.textContent = order;

            const spCard = document.querySelector(`.js-sp-card[data-id="${id}"]`);
            if (spCard) {
                const spLabel = spCard.querySelector('.js-sp-sort-order-label');
                if (spLabel) spLabel.textContent = order;
            }
        });
    }

    // 並び替えボタン
    sortModeButton?.addEventListener('click', enterSortMode);

    // キャンセルボタン
    cancelSortButton?.addEventListener('click', function () {
        leaveSortMode(true);
    });

    // 保存ボタン
    saveSortButton?.addEventListener('click', async function () {
        // 現在の順番でID配列を作る
        const orderedIds = Array.from(document.querySelectorAll('#pc-sortable-body .js-sort-row'))
            .map(row => row.dataset.id);

        // CSRFトークン取得
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ボタン無効化
        saveSortButton.disabled = true;
        saveSortButton.textContent = '保存中...';

        try {
            const response = await fetch('/categories/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
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

            alert(data.message);
            window.location.reload();

        } catch (error) {
            alert('表示順の保存に失敗しました');
            console.error(error);
        } finally {
            saveSortButton.disabled = false;
            saveSortButton.textContent = '保存する';
        }
    });

    // =============================
    // ドラッグ処理
    // =============================
    sortableRows.forEach(row => {

        // ドラッグ開始
        row.addEventListener('dragstart', function () {
            if (!isSortMode) return;
            // 今の行を保持
            draggedItem = this;
            this.classList.add('opacity-60');
        });

        // ドロップ許可
        row.addEventListener('dragend', function () {
            this.classList.remove('opacity-60');
            sortableRows.forEach(r => r.classList.remove('ring-2', 'ring-accent-100'));
        });

            // ドロップ時
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

            // 行の順番を入れ替える
            if (draggedIndex < targetIndex) {
                tbody.insertBefore(draggedItem, this.nextSibling);
            } else {
                tbody.insertBefore(draggedItem, this);
            }

            // 表示順更新
            updateSortOrderLabels();
        });
    });

    // 初期表示で番号を整える
    updateSortOrderLabels();
});