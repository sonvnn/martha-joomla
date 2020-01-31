<?php 
defined('_JEXEC') or die( 'Restricted access' ); 


$produce = $this->produce;
preg_match('/<img.*?src="(.*?)".*?\/>/i', $produce->description, $match);
$img        =   $match ? $match[1] : 'components/com_tz_membership/assets/images/no-image.jpg';
?>

<script type="text/javascript">

	function submitform(pressbutton){
		if (pressbutton) {
			document.adminForm.task.value=pressbutton;
		}
		if (typeof document.adminForm.onsubmit == "function") {
			document.adminForm.onsubmit();
		}
		document.adminForm.submit();
	}
	
	function setgood() {
		return true;
	}
	
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		
		try {
			form.onsubmit();
		} catch(e) {
			alert(e);
		}
		
		submitform(pressbutton);
	}
	function submitbuttonItem(pressbutton, pkid) {
		var form = document.adminForm;

		try {
			form.onsubmit();
		} catch(e) {
			alert(e);
		}

		if(document.getElementsByName('pkid').length) {
			var pkinput = document.getElementsByName('pkid')[0];
			pkinput.value	= pkid;
		}

		submitform(pressbutton);
	}
	
	function package_show(id) {
		if(id == 1) {
			$('fullpackage').setStyle('display', 'block');
			$('patchpackage').setStyle('display', 'none');
		}else {
			$('fullpackage').setStyle('display', 'none');
			$('patchpackage').setStyle('display', 'block');
		}
	}

</script>
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo JText::_('Download'); ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">
    <div class="card-body">
        <div class="information d-flex">
            <div class="product-logo float-left mr-4">
                <img src="<?php echo $img; ?>" alt="<?php echo $produce->produce_name; ?>" />
            </div>
            <div class="d-flex flex-column justify-content-center">
                <h2 class="card-title"><?php echo $produce->produce_name; ?> <small class="badge badge-primary">
                        <?php
                        if(isset($produce->version))
	                        echo ($produce->version?$produce -> version:'N/A');
                        ?>
                    </small></h2>
                <p class="card-text"><?php echo strip_tags($produce->description); ?></p>
            </div>
        </div>

        <form action="<?php echo $this->action; ?>" id="adminForm" name="adminForm" method="post" onSubmit="setgood();">
            <table width="100%" cellspacing="1" class="table table-hover mt-3">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Version</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
			    <?php if($this -> rows){
				    foreach($this -> rows as $i => $item){?>
                        <tr>
                            <td><?php echo $i+1?></td>
                            <td><?php echo $item -> name;?></td>
                            <td><?php echo $item -> type;?></td>
                            <td><?php echo (isset($item -> version) && $item -> version)?$item -> version:'N/A';?></td>
                            <td>
	                            <?php
	                            if(!$item -> canDownload){
		                            ?>
                                    <button type="button" class="btn btn-danger btn-sm disabled"><?php echo JText::_('Pro Only'); ?></button>
		                            <?php
	                            }else{?>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="submitbuttonItem('downloadnow', <?php
		                            echo $item -> id;?>);"><?php echo JText::_('Download'); ?></button>
	                            <?php } ?>
                            </td>
                        </tr>
				    <?php } } ?>
                </tbody>
            </table>

            <input type="hidden" name="pc" value="<?php echo $produce->produce_code; ?>"/>
            <input type="hidden" name="view" value="downloadinfo" />
            <input type="hidden" name="option" value="com_tz_membership"/>
            <input type="hidden" name="pkid" value=""/>
		    <?php echo JHTML::_( 'form.token' ); ?>
            <input type="hidden" name="task" value="" />
        </form>
	    <?php echo JHTML::_('behavior.keepalive'); ?>
    </div>
</div>