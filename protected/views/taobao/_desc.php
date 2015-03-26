<?php if ($this->beginCache('desc' . $model->id . strtotime($model->updateTime) . $model->city, array('duration' => 3600))) { ?>
    <div style="max-height: 600px;
         overflow: auto;">
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >商家说明</h3>
            </div>
            <p><?php echo $model->setAddInfo(); ?></p>
        </div></p>
        
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >商品展示</h3>
            </div>
            <p><?php echo $model->getImagesDesc(); ?>
        </div></p>
        
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >商品展示</h3>
            </div>
            <p><?php echo $model->getImagesDesc(); ?>
        </div></p>
        
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >商品介绍</h3>
            </div>
            <p><?php echo $model->details; ?>
        </div></p>
        
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >商品描述</h3>
            </div>
            <p><?php echo $model->desc; ?>
        </div></p>
       
        <p><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >设计师</h3>
            </div>
            <p><?php echo $model->designer; ?>
        </div></p>
        
        <p align='center'><div style="background-color: #FFF;">
            <div style="border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;">
                <h3 style="color:#FFF;margin: 5px;" >尺寸描述</h3>
            </div>
            <p><?php echo $model->sizeFitContainer; ?>
        </div></p>

    </div>
    <?php $this->endCache();
} ?>