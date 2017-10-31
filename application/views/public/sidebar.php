        <!--BEGIN SIDEBAR MENU-->
        <nav id="sidebar" role="navigation" data-step="2" data-intro="Template has &lt;b&gt;many navigation styles&lt;/b&gt;"
                data-position="right" class="navbar-default navbar-static-side">
            <div class="sidebar-collapse menu-scroll ">
                <ul id="side-menu" class="nav dropdown_sidebar">
                    
                    <div class="clearfix"></div>
                    <li class="<?php if($sidebar_title=='home'){echo 'active';}?>">
                        <a href="<?php echo base_url('home/index')?>">
                            <i class="fa fa-tachometer fa-fw">
                                <div class="icon-bg bg-orange"></div>
                            </i>
                            <span class="menu-title">分组方案</span>
                        </a>
                    </li>
                    
                    <li class="<?php if($sidebar_title=='alpha'){echo 'active';}?>">
                        <a href="<?php echo base_url('alpha/index')?>">
                            <i class="fa fa-send-o fa-fw">
                                <div class="icon-bg bg-green"></div>
                            </i>
                            <span class="menu-title">Alpha多样性分析</span>
                            
                            <!-- <ul>
                              <li><a href="#">Submenu 1</a></li>
                              <li><a href="#">Submenu 2</a></li>
                              <li><a href="#">Submenu 3</a></li>
                            </ul> -->
                        </a>
                        <!-- <ol>
                           <a href="<?php echo base_url('alpha/index')?>">
                            <i class="fa fa-send-o fa-fw">
                                <div class="icon-bg bg-green"></div>
                            </i>
                            <span class="menu-title">Alpha多样性分析</span>
                        </a>
                            <a href="<?php echo base_url('alpha/index')?>">
                            <i class="fa fa-send-o fa-fw">
                                <div class="icon-bg bg-green"></div>
                            </i>
                            <span class="menu-title">Alpha多样性分析</span>
                        </a>
                        </ol> -->
                    </li>

                    <li class="<?php if($sidebar_title=='beta'){echo 'active';}?>">
                        <a href="<?php echo base_url('beta/index')?>">
                            <i class="fa fa-desktop fa-fw">
                                <div class="icon-bg bg-pink"></div>
                            </i>
                            <span class="menu-title">Beta多样性分析</span>
                        </a>
                    </li>

                    <!-- </li>
                    <li><a href=""><i class="fa fa-edit fa-fw">
                        <div class="icon-bg bg-violet"></div>
                    </i><span class="menu-title">Forms</span></a>
                      
                    </li> -->
                    <!-- <li><a href=""><i class="fa fa-th-list fa-fw">
                        <div class="icon-bg bg-blue"></div>
                    </i><span class="menu-title">Tables</span></a>
                          
                    </li>
                    <li><a href=""><i class="fa fa-database fa-fw">
                        <div class="icon-bg bg-red"></div>
                    </i><span class="menu-title">Data Grids</span></a>
                      
                    </li>
                    <li><a href=""><i class="fa fa-file-o fa-fw">
                        <div class="icon-bg bg-yellow"></div>
                    </i><span class="menu-title">Pages</span></a>
                       
                    </li>
                    <li><a href=""><i class="fa fa-gift fa-fw">
                        <div class="icon-bg bg-grey"></div>
                    </i><span class="menu-title">Extras</span></a>
                      
                    </li>
                    <li><a href=""><i class="fa fa-sitemap fa-fw">
                        <div class="icon-bg bg-dark"></div>
                    </i><span class="menu-title">Multi-Level Dropdown</span></a>
                      
                    </li>
                    <li><a href=""><i class="fa fa-envelope-o">
                        <div class="icon-bg bg-primary"></div>
                    </i><span class="menu-title">Email</span></a>
                      
                    </li>
                    <li><a href=""><i class="fa fa-bar-chart-o fa-fw">
                        <div class="icon-bg bg-orange"></div>
                    </i><span class="menu-title">Charts</span></a>
                       
                    </li>
                    <li><a href=""><i class="fa fa-slack fa-fw">
                        <div class="icon-bg bg-green"></div>
                    </i><span class="menu-title">Animations</span></a></li> -->
                </ul>
            </div>
        </nav>
            <!--END SIDEBAR MENU-->
<!-- <script src="<?php echo base_url('static/js/tendina.js')?>"></script>
<script>
      $('.dropdown_sidebar').tendina({
        animate: true,
        speed: 500,
        openCallback: function($clickedEl) {
          console.log($clickedEl);
        },
        closeCallback: function($clickedEl) {
          console.log($clickedEl);
        }
      })
</script> -->