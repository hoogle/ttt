<?php $this->load->view("l10n/l10n/before_body"); ?>
<div id="hd">
    <div class="yui-d3">
<?php $this->load->view("l10n/l10n/masthead"); ?>
<?php $this->load->view("l10n/l10n/navigation"); ?>
    </div>
</div>
<div id="bd">
    <div class="yui-d3">
        <div class="yui-t4">
            <div class="yui-main">
                <div class="yui-b">
<?php echo $content_for_layout; ?>
                </div><!-- .yui-b (end)  -->
            </div><!-- .yui-main (end) -->
            <div class="yui-b">
<?php $this->load->view("l10n/l10n/sidebar"); ?>
            </div><!-- .yui-b (end)  -->
        </div><!-- .yui-t4 (end) -->
    </div><!-- .yui-d3 (end) -->
</div><!-- #bd (end) -->
<div id="ft">
    <div class="yui-d3">
<?php $this->load->view("l10n/l10n/mastfoot"); ?>
    </div><!-- .yui-d3 (end) -->
</div><!-- #ft (end) -->
<?php $this->load->view("l10n/l10n/after_body"); ?>
