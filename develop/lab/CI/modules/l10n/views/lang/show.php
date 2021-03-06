<?php echo modules::run("l10n/component/show_cate_select"); ?>
<span id="totalRecords"></span>
<div id="dynamicdata"></div>
<script type="text/javascript"> 
YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.util.Event.on('pagecate1', 'change', function(e) {
        var sel_val2 = show_item(2, e.target.value);
        var sel_val3 = show_item(3, sel_val2);
        page_cate_pid = (sel_val3 == undefined) ? sel_val2 : sel_val3;
        show_langs();
    });

    YAHOO.util.Event.on('pagecate2', 'change', function(e) {
        var sel_val3 = show_item(3, e.target.value);
        page_cate_pid = (sel_val3 == undefined) ? e.target.value : sel_val3;
        show_langs();
    });

    YAHOO.util.Event.on('pagecate3', 'change', function(e) {
        page_cate_pid = e.target.value;
        show_langs();
    });

    var show_langs = function() {
        YAHOO.example.InlineCellEditing = function() {

            var formatLink = function(elCell, oRecord, oColumn, oData) {
                elCell.innerHTML = "<a href=\"/l10n/lang/edit/" + oData + "\">" + oData + "</a>";
            };

            var formatTranslate = function(elCell, oRecord, oColumn, oData) {
                elCell.innerHTML = "<pre class=\"translate\">" + oData + "</pre>";
            };

            var myColumnDefs = [
<?php if ( ! $level) : ?>
                {key:"s_id", label:"ID", sortable:true, formatter:formatLink},
<?php else : ?>
                {key:"s_id", label:"ID", sortable:true},
<?php endif ?>
                {key:"key_word", label:"Key word", sortable:true},
                {key:"translate", editor: new YAHOO.widget.TextboxCellEditor(), formatter:formatTranslate},
                {key:"status", label:"Status", sortable:true}
            ];

            // DataSource instance
            var dataSourceURL = '/l10n/lang/jsonlist?page_id='+page_cate_pid+'&t='+new Date().valueOf()+'&';
            var myDataSource = new YAHOO.util.DataSource(dataSourceURL);
            myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            myDataSource.responseSchema = {
              resultsList: "records",
              fields: [
                  {key:"s_id"},
                  {key:"key_word"},
                  {key:"translate"},
                  {key:"status"}
              ],
              metaFields: {
                totalRecords: "totalRecords" // Access to value in the server response
              }
            };

            // DataTable configuration
            var myConfigs = {
                initialRequest: "sort=s_id&dir=asc&startIndex=0&results=10",
                dynamicData: true, // Enables dynamic server-driven data
                sortedBy : {key:"s_id", dir:YAHOO.widget.DataTable.CLASS_ASC},
                paginator: new YAHOO.widget.Paginator({ rowsPerPage:10 }) // Enables pagination
                //scrollable:true, width:"900px"
            };

            var myDataTable = new YAHOO.widget.DataTable("dynamicdata", myColumnDefs, myDataSource, myConfigs);
            myDataTable.subscribe("cellClickEvent", function(oArgs) {
                current_sid = (document.uniqueID) ? oArgs.target.parentNode.firstChild.innerText : oArgs.target.parentNode.firstChild.textContent;
                myDataTable.onEventShowCellEditor(oArgs);
            }); 

            var updCallback = {
                success: function(o) {
                    if (o.responseText == '') {
                        alert('Please login!');
                        window.location = '/l10n/login/index/' + location.pathname.replace(/\//g, ',');
                    } else {
                        alert('Re-translated OK!');
                    }
                },
                failure: function(o) {
                    alert('System busy, wait for a while... Code:' + o.responseText);
                    window.location = '/l10n/';
                }
            };

            var updRequest = function(El) {
                var dataStr = [
                    'use_lang=<?php echo $use_lang; ?>',
                    's_id='+current_sid,
                    'page_id='+page_cate_pid,
                    'translate='+encodeURIComponent(El.value)
                ].join('&'); 
                YAHOO.util.Connect.asyncRequest('POST', '/l10n/lang/update/', updCallback, dataStr);
            };

            myDataTable.subscribe("editorKeydownEvent", function(oArgs) {
                if (oArgs.event.keyCode == 13) {
                    updRequest(oArgs.event.target);
                }
            });

            YAHOO.util.Event.on("yui-textboxceditor0-container", "click", function(e) {
                var targetEl = YAHOO.util.Event.getTarget(e);
                if (YAHOO.util.Dom.hasClass(targetEl, "yui-dt-default")) {
                    updRequest(targetEl.parentNode.parentNode.getElementsByTagName('INPUT')[0]);
                }
            });

            myDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
              oPayload.totalRecords = oResponse.meta.totalRecords;
              YAHOO.util.Dom.get('totalRecords').innerHTML = 'Total <span style="color:teal;">' + oPayload.totalRecords + '</span> records';
              return oPayload;
            }

            return {
                ds: myDataSource,
                dt: myDataTable
            };
        }();
    };

});
</script>
