<div class="search" style="float:right;">
<table>
 <tr>
  <td>
   <table><tr><td>
    <img src="<?= $config['icon_path'] ?>search.png" width="16" height="16" /> Rechercher :
     <?= Search::search_box() ?>
   </td></tr></table>
  </td>
 </tr>
</table>
</div>
<div>Index de <?= $this->path_nav ?></div>
<table class="table table-striped" style="clear:right;">
 <tr>
  <th>Fichier</th>
  <th>Taille</th>
  <th>Modifié</th>
 </tr>
