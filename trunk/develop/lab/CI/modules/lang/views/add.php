<form id="Fadd" name="Fi" method="post" action="/lang/ins">
  <ul>
    <li>key: <input type="text" name="key_word"/></li>
<?php foreach($lang_arr as $lang_item) : ?>
    <li><?php echo $lang_item['l_name']?>: <input type="text" name="<?php echo $lang_item['l_type']?>_word"/></li>
<?php endforeach ?>
  </ul>
  <input type="button" id="btn_new" name="send" value=" ADD NEW "/>
</form>
<script type="text/javascript">
var $ = YAHOO.util.Dom.get,
    PB = new YAHOO.widget.Button("btn_new");

PB.on('click', function() {
    $('Fadd').submit();
});
</script>
