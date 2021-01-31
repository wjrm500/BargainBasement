$(document).ready(function() {
    let firstPageButton = document.querySelector('button[data-page-num="0"]');
    highlightButton(firstPageButton);

    $('#admin-table-page-buttons').children().each(function() {
        $(this).click(function() {
            let pageNum = $(this).data('page-num');
            goToPage(pageNum);
        });
    });

    $('#pagination-back-all').click(function() {
        goToPage(0);
    });

    $('#pagination-forward-all').click(function() {
        let maxPageNum = getMaxPageNum();
        goToPage(maxPageNum);
    });

    $('#pagination-back-one').click(function() {
        // debugger;
        let currentPageNum = getCurrentPageNum();
        if (currentPageNum !== 0) {
            goToPage(currentPageNum - 1);
        }
    });

    $('#pagination-forward-one').click(function() {
        let currentPageNum = getCurrentPageNum();
        if (currentPageNum !== getMaxPageNum()) {
            goToPage(currentPageNum + 1);
        }
    });
});

function goToPage(pageNum) {
    let button = getButtonByPageNum(pageNum);
    highlightButton(button);
    let table = getTableByPageNum(pageNum);
    displayTable(table);
}


function highlightButton(button) {
    if (!(button instanceof jQuery)) {
        button = $(button);
    }
    button.attr('data-selected', 'true');
    button.siblings().each(function() {
        $(this).attr('data-selected', 'false')
    });
    button.removeClass('btn-light');
    button.addClass('btn-success');
    button.siblings().each(function() {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-light');
    });
}

function displayTable(table) {
    if (!(table instanceof jQuery)) {
        table = $(table);
    }
    table.attr('data-selected', 'true');
    table.siblings().each(function() {
        $(this).attr('data-selected', 'false')
    });
    table.removeClass('d-none');
    table.siblings().addClass('d-none');
}


function getTableByPageNum(pageNum) {
    let tables = document.querySelectorAll('.admin-table-page');
    for (let table of tables) {
        if (table.dataset.pageNum == pageNum) {
            return table;
        }
    }
}

function getButtonByPageNum(pageNum) {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    for (let button of buttons) {
        if (button.dataset.pageNum == pageNum) {
            return button;
        }
    }
}

function getMaxPageNum() {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    let lastButton = buttons[buttons.length - 1];
    return parseInt(lastButton.dataset.pageNum);
}

function getCurrentPageNum() {
    let buttons = document.querySelectorAll('.admin-table-page-button');
    for (let button of buttons) {
        if (button.dataset.selected == 'true') {
            return parseInt(button.dataset.pageNum);
        }
    }
}