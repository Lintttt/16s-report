<?php $this->load->view("public/topbar"); ?>
<div id="wrapper">
<?php $this->load->view("public/sidebar"); ?>
    <div id="page-wrapper">
		<!--BEGIN TITLE & BREADCRUMB PAGE-->
        <div class="page-title-breadcrumb option-demo">
            <div class="page-header pull-right">
                <div class="page-toolbar">
                    
                </div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-left">
                <li><i class="fa fa-home"></i>&nbsp;<a href="index">Beta多样性分析</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                <li class="active">Beta</li>
            </ol>
            <div class="clearfix">
            </div>
        </div>
		<!--END TITLE & BREADCRUMB PAGE-->
        <div class="page-content" id="app">
			<div id="tab-general">
	            <div class="row mbl">
	                <div class="col-lg-12" id="shannon_div" >
                        <div class="row">

                            <div class="panel">
                                <div class="panel-heading">
                                    <ul id="myTab" class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#Beta多样性指数" data-toggle="tab">Beta多样性指数</a>
                                        </li>
                                        <li role="presentation" class="dropdown">
                                        	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">样品信息分析<span class="caret"></span></a>
									        <ul class="dropdown-menu">
                                                <li><a href="#PCA分析" data-toggle="tab">PCA分析</a></li>
									        	<li><a href="#PCoA分析" data-toggle="tab">PCoA分析</a></li>
									            <li><a href="#NMDS分析" data-toggle="tab">NMDS分析</a></li>
									            <li><a href="#UPGMA聚类分析" data-toggle="tab">UPGMA聚类分析</a></li>
									        </ul>
								        </li>
                                        <li><a href="#unifrac距离差异分析" data-toggle="tab">unifrac距离差异分析</a></li>
								        <li role="presentation" class="dropdown">
                                        	<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">差异分析<span class="caret"></span></a>
									        <ul class="dropdown-menu">
									        	<li><a href="#Adonis差异分析" data-toggle="tab">Adonis差异分析</a></li>
									            <li><a href="#Anosim差异分析" data-toggle="tab">Anosim差异分析</a></li>
									        </ul>
								        </li>
                                    </ul>
                                </div>
                                <div class="panel-body" style="min-height: 600px;">
                                    <div id="myTabContent" class="tab-content">
                                        <div class="tab-pane fade in active" id="Beta多样性指数">
                                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Beta多样性是不同生态系统之间多样性的比较，是物种组成沿环境梯度或者在群落间的变化率， 用来表示生物种类对环境异质性的反应。一般来说，不同环境梯度下群落Beta多样性计算包括物种改变（多少） 和物种产生（有无）两部分。因此，我们将根据这两个重要指标，运用Weighted Unifrac和Unweighted Unifrac 两个指数进行后续Beta多样性分析。<br>&nbsp;&nbsp;&nbsp;&nbsp;在微生物β多样性多样性研究中，Weighted Unifrac和Unweighted Unifrac是常用的样本距离计算方法。 相比于Jaccard和Bray-Curtis等距离计算方式，Unifrac充分考虑OTU代表序列之间的进化关系（碱基变异信息）， 从而更符合微生物群落变化的实际生物学意义。<br>&nbsp;&nbsp;&nbsp;&nbsp;Unifrac除了具有考虑OTU之间的进化关系的特点之外，根据有没有考虑OTU丰度的区别， 可分为加权（WeightedUunifrac）和非加权（Unweighted Unifrac）两种方法。 其中Unweighted UniFrac只考虑了物种有无的变化，而Weighted UniFrac则同时考虑物种有无和物种丰度的变化。 因此在实际分析中，结合两种Unifrac分析方法，能更有效发现样本之间的结构差异信息。</p>
                                        </div>
                                        <div class="tab-pane fade" id="PCA分析">
                                            <p>基于OTU列表的物种丰度信息，可以开展主成分分析（PCA，Principal Component Analysis）， 从而利用利用降维的思想研究样本间的组成距离关系。这种方法借用方差分解可以有效的找出数据中最“主要”的元素和结构 ，将复杂的样本组成关系反映到横纵坐标的两个特征值上，从而达到简化数据复杂度的效果。分析结果中，样品组成越相似， 反映在PCA 图中的距离越近，而且不同环境间的样品往往可能表现出各自聚集的分布情况。</p>
                                        </div>
                                        <div class="tab-pane fade" id="PCoA分析">
                                            <p>PCoA主坐标分析是一种展示样本间相似性的分析方式，它的分析思路与PCA分析基本一致， 都是通过降维方式寻找复杂样本中的主要样本差异距离。与PCA不同的是，PCoA主要利用weighted和 unweighted Unifrac等配对信息，因此结果更集中于体现样本间的相异性距离。<br><br>基于样本间的weighted和unweighted Unifrac数据结果，我们可以绘制PCoA图形。分析结果中， 样品越相似，反映在PCoA 图中的距离越近，而且不同环境间的样品往往可能表现出各自聚集的分布情况。</p>
                                        </div>
                                        <div class="tab-pane fade" id="NMDS分析">
                                            <p>NMDS是非线性模型，适用于无法获得研究对象间精确的相似性或相异性数据， 其设计目的是为了克服线性模型（包括PCA、 PCoA）的缺点，更好地反映生态学数据的非线性结构。 我们根据weighted和unweighted Unifrac矩阵，进行NMDS分析。其特点是根据样品中包含的物种信息， 以点的形式反映在多维空间上，而对不同样品间的差异程度，则是通过点与点间的距离体现的， 最终获得样品的空间定位点图。</p>
                                        </div>
                                        <div class="tab-pane fade" id="UPGMA聚类分析">
                                            <p>在微生物生态研究当中，UPGMA分类树可以用于研究样本间的相似性，解答样本的分类学问题。 利用Mothur软件，根据weighted和unweighted Unifrac矩阵信息，可以将样本进行UPGMA分类树分类。 其中越相似的样本将拥有越短的共同分支。</p>
                                        </div>
                                        <div class="tab-pane fade" id="unifrac距离差异分析">
                                            <p>不同生境下的环境驱动因素能引起微生物β多样性差异。结合分组和采样信息， 通过对两组或者多组间的unifrac距离进行进行假设检验， 可以分析组间的物种多样性是否存在显著的差异， 从而初步判断驱动群落多样性变化的潜在因素等。我们同时使用以下几种常见的假设检验方法来进行差异分析。
                                            <br><br>（1）针对2个分组进行比较时，使用T-test检验和wilcoxon秩和检验；<br>（2）针对2个以上分组进行比较时，使用Tukey HSD检验和Kruskal-Wallis秩和检验。<br>注：上述的检验方法均要求每组至少含有3个重复样本。</p>
                                        </div>
                                        <div class="tab-pane fade" id="Adonis差异分析">
                                            <p>Adonis是一种基于Unifrac等距离矩阵的非参数多元方差分析方法。 该方法可分析不同分组因素对样品差异的解释度，并使用置换检验对分组的统计学意义进行显著性分析。</p>
                                        </div>
                                        <div class="tab-pane fade" id="Anosim差异分析">
                                            <p>Analysis of Similarity (ANOSIM)分析是一种对微生物群落结构的非参数检验方法， 用来检验组间的差异是否显著大于组内差异，从而判断分组是否有意义。</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


	                            	

