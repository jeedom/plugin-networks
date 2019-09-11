<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('networks');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
 <div class="col-lg-12 eqLogicThumbnailDisplay">
  <legend><i class="fa fa-table"></i> {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
	<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
	  	</div>
		<div class="eqLogicThumbnailContainer">
				<legend><i class="fas fa-table"></i> {{Mes networks}}</legend>
			<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
    <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
	echo '<img src="' . $plugin->getPathImgIcon() . '" />';
	echo "<br>";
	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
}
?>
 </div>

</div>

<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <br/>
      <form class="form-horizontal">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-3 control-label">{{Nom de l'équipement networks}}</label>
            <div class="col-sm-3">
              <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
              <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement networks}}"/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
            <div class="col-sm-3">
              <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                <option value="">{{Aucun}}</option>
                <?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
             </select>
           </div>
         </div>
         <div class="form-group">
           <label class="col-sm-3 control-label">{{Catégorie}}</label>
           <div class="col-sm-8">
            <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
         </div>
       </div>
       <div class="form-group">
         <label class="col-sm-3 control-label"></label>
         <div class="col-sm-8">
          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Adresse IP}}</label>
        <div class="col-sm-3">
          <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Adresse MAC (wol)}}</label>
        <div class="col-sm-3">
          <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mac"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Broadcast IP (wol)}}</label>
        <div class="col-sm-3">
          <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="broadcastIP"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Méthode de ping}}</label>
        <div class="col-sm-3">
          <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="pingMode">
            <option value="ip">{{IP}}</option>
            <option value="arp">{{ARP}}</option>
            <option value="port">{{Port}}</option>
          </select>
        </div>
      </div>
      <div class="form-group pingMode ip">
        <label class="col-sm-3 control-label">{{TTL}}</label>
        <div class="col-sm-3">
          <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ttl"/>
        </div>
      </div>
       <div class="form-group pingMode port">
        <label class="col-sm-3 control-label">{{Port}}</label>
        <div class="col-sm-3">
          <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Auto-actualisation (cron)}}</label>
        <div class="col-sm-2">
          <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Auto-actualisation (cron)}}"/>
        </div>
        <div class="col-sm-1">
          <i class="fa fa-question-circle cursor floatright" id="bt_cronGenerator"></i>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">{{Notifier si le ping est KO}}</label>
        <div class="col-sm-2">
          <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="notifyifko"/>
        </div>
      </div>
    </fieldset>
  </form>

</div>
<div role="tabpanel" class="tab-pane" id="commandtab">
  <br/>
  <table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
      <tr>
        <th style="max-width : 200px;">{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
</div>
</div>
</div>

<?php include_file('desktop', 'networks', 'js', 'networks');?>
<?php include_file('core', 'plugin.template', 'js');?>
