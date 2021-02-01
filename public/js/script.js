$(document).ready(function() {
    // Highlight currently selected page in admin navbar

    let listChildren = $('#admin-nav-list').children();
    for (let listChild of listChildren) {
        let button = $($(listChild).children()[0]);
        if (button.attr('href') === window.location.pathname) {
            applyCurrentlySelectedGlow(button);
        }
    }

    function applyCurrentlySelectedGlow(button) {
        button.css({
            'color': 'white'
        });
        let buttonParent = $(button.parent());
        buttonParent.css({
            'background': 'linear-gradient(90deg, rgb(0,150,0), rgb(150,225,150), white)',
        })
    }
});
$(document).ready(function() {
    let firstPageButton = document.querySelector('button[data-page-num="0"]');
    highlightButton(firstPageButton);

    $('#pagination-direct').keypress(function(e) {
        if (e.which === 13) {
            let inputValue = $('#pagination-direct').val();
            if (isNaN(inputValue)) {
                return alert('Please enter a number');
            }
            goToPage(inputValue - 1);
        }
    });

    $('#pagination-back-all').click(function() {
        goToPage(0);
    });
    
    $('#pagination-back-one').click(function() {
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
    
    $('#pagination-forward-all').click(function() {
        let maxPageNum = getMaxPageNum();
        goToPage(maxPageNum);
    });

    $('#admin-table-page-buttons').children().each(function() {
        $(this).click(function() {
            let pageNum = $(this).data('page-num');
            goToPage(pageNum);
        });
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
$(document).ready(
    function() {
        // Add glowing border to current page in secondary navbar
        let beforeSlash = window.location.pathname.split('/')[1];
        let navItem = document.getElementById('nav-item-' + beforeSlash);
        navItem.style.border = '2px solid';
        navItem.style.borderColor = 'dodgerblue';
        navItem.style.borderRadius = '5px';
        navItem.style.boxShadow = "0px 0px 5px 3px dodgerblue";
    }
)

