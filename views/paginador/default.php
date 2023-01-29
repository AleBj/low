<?php if(isset($this->_paginacion)): ?>


    <ul id="paginador">
       

        <?php if($this->_paginacion['anterior']): ?>

            <li style="display:inline-block"><a href="<?php echo $link . $this->_paginacion['anterior']; ?>"><img src="http://200.58.108.42/~cybermon/views/layout/wmt/img/prev-paginador.jpg" alt="previo" /></a></li>

        <?php else: ?>

            <li style="display:inline-block"><a class="current"><img src="http://200.58.108.42/~cybermon/views/layout/wmt/img/prev-paginador.jpg" alt="previo" /></a></li>

        <?php endif; ?>

        <?php for($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>

            <?php if($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>

                <li style="display:inline-block"><a class="current"><?php echo $this->_paginacion['rango'][$i]; ?></a></li>

            <?php else: ?>

                <li style="display:inline-block">
                    <a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>">
                        <?php echo $this->_paginacion['rango'][$i]; ?>
                    </a>
                </li>

            <?php endif; ?>

        <?php endfor; ?>

        <?php if($this->_paginacion['siguiente']): ?>

            <li style="display:inline-block"><a href="<?php echo $link . $this->_paginacion['siguiente']; ?>"><img src="http://200.58.108.42/~cybermon/views/layout/wmt/img/next-paginador.jpg" alt="siguiente" /></a></li>

        <?php else: ?>

            <li style="display:inline-block"><a class="current"><img src="http://200.58.108.42/~cybermon/views/layout/wmt/img/next-paginador.jpg" alt="siguiente" /></a></li>

        <?php endif; ?>
    </ul>
</div>

<?php endif; ?>