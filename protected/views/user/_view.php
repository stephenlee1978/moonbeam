<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
    <?php echo CHtml::encode($data->username); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
    <?php echo CHtml::encode($data->password); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('nickname')); ?>:</b>
    <?php echo CHtml::encode($data->nickname); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
    <?php echo CHtml::encode($data->email); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('logintime')); ?>:</b>
    <?php echo CHtml::encode($data->logintime); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
    <?php echo CHtml::encode($data->admin); ?>
    <br />

    <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
      <?php echo CHtml::encode($data->active); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('outtime')); ?>:</b>
      <?php echo CHtml::encode($data->outtime); ?>
      <br />

     */ ?>

</div>