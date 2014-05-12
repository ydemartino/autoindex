<?php foreach($this->contents as $file) { ?>
 <tr<?= $file -> is_being_written ? ' class="being_written"' : '' ?>>
  <td>
   <a href="<?= is_file($file -> parent_dir . $file -> filename) ? $file -> parent_dir . $file -> filename : $file -> link ?>">
    <img width="16" height="16" src="<?= $file -> icon ?>" />
    <?= $file -> filename ?>
   </a><?= $file -> new_icon ?>
  </td>
  <td class="size">
   <?= $file -> size ?>
  </td>
  <td class="last_mod">
   <?= $file -> format_m_time() ?>
  </td>
 </tr>
 <?php } ?>
