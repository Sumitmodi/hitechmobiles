//<![CDATA[ 

var selectionPivot;
// 1 for left button, 2 for middle, and 3 for right.
var LEFT_MOUSE_BUTTON = 1;

var trs = document.getElementById('tableSorter').tBodies[0].getElementsByTagName('tr');
var idTds = $('tbody tr');
idTds.each(function (idx, val) {
    // onselectstart because IE doesn't respect the css `user-select: none;`
    val.onselectstart = function () {
        return false;
    };
    $(val).mousedown(function (event) {
        if (event.which != LEFT_MOUSE_BUTTON) {
            return;
        }
        var row = trs[idx];

        if (!event.ctrlKey && !event.shiftKey) {
            clearAll();
            toggleRow(row);
            selectionPivot = row;
            selectedRows();
            return;
        }

        if (event.ctrlKey && event.shiftKey) {
            selectRowsBetweenIndexes(selectionPivot.rowIndex, row.rowIndex);
            selectedRows();
            return;
        }

        if (event.ctrlKey) {
            toggleRow(row);
            selectionPivot = row;
        }
        if (event.shiftKey) {
            clearAll();
            selectRowsBetweenIndexes(selectionPivot.rowIndex, row.rowIndex);
        }
        selectedRows();
    });
});

function toggleRow(row) {
    row.className = row.className == 'selected' ? '' : 'selected';

}

function selectRowsBetweenIndexes(ia, ib) {
    var bot = Math.min(ia, ib);
    var top = Math.max(ia, ib);
    for (var i = bot; i <= top; i++) {
        trs[i - 1].className = 'selected';
        $(trs[i - 1]).find('input').attr('checked', true);
    }
}
function selectedRows() {
    $('tbody tr td input').prop('checked', false);
    var idTds1 = $('tbody tr.selected');
    //return;
    idTds1.each(function (idx, val) {
        //console.log(val);
        //    console.log($(val).find('input').prop('checked'));
        $(val).find('input').prop('checked', true);
    });
    return;
}
function clearAll() {
    for (var i = 0; i < trs.length; i++) {
        trs[i].className = '';
        $(trs[i - 1]).find('input').attr('checked', false);
    }
}

function performToSelection(select) {
    if ($(select).val() == 'selectall') {
        selectTable(true);
    }
    if ($(select).val() == 'selectnone') {
        selectTable(false);
    }
    if ($(select).val() == 'keepselected') {

        if ($('input:checked').length > 0) {
            $selectId = '';
            $("#tablesorter tbody tr.selected").each(function (idx, val) {
                $selectId += $(trs[idx]).find('input').val() + ":";
            });
            $.post('/admin/keep_omit/C/K/' + $selectId, function () {
                $("#tablesorter tbody tr").each(function (idx, val) {
                    if (!$(this).hasClass('selected')) {
                        $(this).hide();
                    }
                });
            });


            //console.log($selectId);
        } else {
            alert('Please select first');
        }
    }
}

function selectTable($checked) {
    $("#tablesorter tbody tr").each(function (idx, val) {
        trs[idx].className = '';
        $(trs[idx]).find('input').prop('checked', $checked);
    });
}
//]]>  