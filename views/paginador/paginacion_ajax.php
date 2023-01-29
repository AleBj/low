<?php if(isset($this->_paginacion)): ?>

<div class="pagination" style="text-align: center;">
    <ul>
    
    
        <?php if($this->_paginacion['primero']): ?>
            <li>
            	<a class="pagina" pagina="<?php echo $this->_paginacion['primero']; ?>" href="javascript:void(0);">
                	&Lt;
                </a>
           </li>
        <?php else: ?>
            <li class="disabled"><span>&Lt;</span></li>
        <?php endif; ?>
        

        <?php if($this->_paginacion['anterior']): ?>
            <li>
            	<a class="pagina" pagina="<?php echo $this->_paginacion['anterior']; ?>" href="javascript:void(0);">
                	&lt;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled"><span>&lt;</span></li>
        <?php endif; ?>
        

        <?php for($q = 0; $q < count($this->_paginacion['rango']); $q++): ?>
            <?php if($this->_paginacion['actual'] == $this->_paginacion['rango'][$q]): ?>
                <li class="active">
                	<span><?php echo $this->_paginacion['rango'][$q]; ?></span>
                </li>
            <?php else: ?>
                <li>
                    <a class="pagina" pagina="<?php echo $this->_paginacion['rango'][$q]; ?>" href="javascript:void(0);">
                        <?php echo $this->_paginacion['rango'][$q]; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>
        

        <?php if($this->_paginacion['siguiente']): ?>
            <li>
            	<a class="pagina" pagina="<?php echo $this->_paginacion['siguiente']; ?>" href="javascript:void(0);">
                	&gt;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled"><span>&gt;</span></li>
        <?php endif; ?>
        

        <?php if($this->_paginacion['ultimo']): ?>
            <li>
            	<a class="pagina" pagina="<?php echo $this->_paginacion['ultimo']; ?>" href="javascript:void(0);">
                	&Gt;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled"><span>&Gt;</span></li>
        <?php endif; ?>
    </ul>
</div>

<div style="text-align: center">
    <p>
        <small>
            Pagina <?php echo $this->_paginacion['actual']; ?> de <?php echo $this->_paginacion['total']; ?>
            <br>
            Registros por pagina: 
            <select id="registros" class="span1">
                <?php for($d = 10; $d <= 100; $d += 10): ?>
                    <option value="<?php echo $d; ?>" <?php if($d == $this->_paginacion['limite']){ echo 'selected="selected"'; } ?>  ><?php echo $d; ?></option>
                <?php endfor;?>
            </select>
        </small>
    </p>
</div>
<?php endif; ?>