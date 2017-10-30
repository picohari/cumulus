<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'INTEWA Monitor';
?>
<div class="site-index">


    <div class="body-content">


        <div class="jumbotron">
            <h1>Observe Greywater and Rainwater systems online</h1>
            <p class="lead">Monitoring solutions for INTEWA greywater systems</p>
            <p><a class="btn btn-lg btn-success" href="<?php echo Url::to(['site/login']); ?>">Get started with AQUALOOP</a></p>
        </div>


        <div class="row center-block">
            <div class="col-md-4">
            <div class="">
                <div class="box-header"><h3 class="page-header"><span class="glyphicon glyphicon-tint">&nbsp;</span>Hardware Components</h3></div>
                <div class="box-body">
                    <ul>
                        <li><a href="http://de.intewa-store.com/de_en/aqualoop/aqualoop-single-membranstation-inkl-membran-und-steuerung.html"><b>AL-MS 1</b> AQUALOOP Membrane Station</a></li>
                        <li><a href="http://de.intewa-store.com/de_en/aqualoop/aqualoop-membranstation-und-steuerung.html"><b>AL-MS</b> AQUALOOP Membrane Station</a></li>
                        <li><a href="http://de.intewa-store.com/de_en/aqualoop/al-ersatzteile/steuergerat-zu-aqualoop-membranstation.html"><b>ALMS-CU</b> Control unit</a></li>
                        <li><a href="http://de.intewa-store.com/de_en/rainmaster/rm-ersatzteile/sensorelektronik-rmd-se3-im-gehause-fur-rainmaster-d-rainmaster-d-24-und-eco-fs.html"><b>RMD-S3 + RMD-SE3</b> (Level sensor) 0-15V</a></li>
                        <li><a href="#"><b>Digital water meters</b>, M Bus</a></li>
                        <li><a href="http://de.intewa-store.com/de_en/aqualoop/al-accessories/al-drucksensor-pumpenuberwachung.html"><b>AL-PCS</b> Pressure sensor</a></li>
                        <li><a href="http://de.intewa-store.com/de_en/aqualoop/al-accessories/al-drucksensor-geblaseuberwachung.html"><b>AL-BCS</b> Pressure sensor</a></li>
                    </ul>
                </div>
                <div class="box-footer"></div>
            </div>
            </div>
            <div class="col-md-4">
            <div class="">
                <div class="box-header"><h3 class="page-header"><span class="glyphicon glyphicon-random">&nbsp;</span>Applications</h3></div>
                <div class="box-body">
                    <ul>
                        <li><a href="http://www.intewa.de/en/applications/loeschwasserbehaelter/">Rain- and fire protection tanks</a></li>
                        <li><a href="http://www.intewa.de/en/applications/rainwater-management/rw-infiltration/">Rain water infiltration</a></li>
                        <li><a href="http://www.intewa.de/en/applications/rainwater-management/rw-retention/">Rain water retention</a></li>
                        <li><a href="http://www.intewa.de/en/applications/rain-greywater-harvesting/greywaterrecycling/">Grey water treatment</a></li>
                        <li><a href="http://www.intewa.de/en/applications/rain-greywater-harvesting/drinking-water-from-rainwater/">Drinking water from rainwater</a></li>
                        <li><a href="http://www.intewa.de/en/applications/rainwater-management/infiltration-of-treated-wastewater/">Effluent from small sewage treatment plants</a></li>
                    </ul>
                </div>
                <div class="box-footer"></div>
            </div>
            </div>
            <div class="col-md-4">
            <div class="">
                <div class="box-header"><h3 class="page-header"><span class="glyphicon glyphicon-calendar">&nbsp;</span>Pricing</h3></div>
                <div class="box-body">
                    <ul>
                        <li>A free version of our service is included in each INTEWA product. Refer to our <a href="<?php echo Url::to(['site/pricing']); ?>">pricing</a> table for more sophisticated features.</li>
                    </ul>
                </div>
                <div class="box-footer"></div>
            </div>
            </div>

        </div>



        <div class="row">
            <div class="col-lg-4">
                <h2><span class="glyphicon glyphicon-cloud">&nbsp;</span>Cloud telemetry</h2>
                <p>Supervise your system over networks with next generation applications. We provide dedicated middleware hosting
                service for all your INTEWA Monitoring solutions.</p>
            </div>
            <div class="col-lg-4">
                <h2><span class="glyphicon glyphicon-cog">&nbsp;</span>Intelligent hardware</h2>
                <p>Access your water treatment system from all over the world. Stay in contact with your applications and get near
                realtime information about your control settings.</p>
            </div>
            <div class="col-lg-4">
                <h2><span class="glyphicon glyphicon-wrench">&nbsp;</span>Easy Support</h2>
                <p>We assist you through the optimal configuration of your system by precise data analysis. Get out of the maximum
                from your treatment process and save more water.</p>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-4"><div class=""><img src="images/pressure_gauge.png">
                </div>
            </div>

            <div class="col-sm-4"><div class=""><img src="images/android_app.png">
                </div>
            </div>

            <div class="col-sm-4"><div class=""><img src="images/remote_support.png">
                </div>
            </div>
        </div>


        <div class="row">
            <div class="jumbotron">
                <h1>Evaluation of Infiltration and Irrigation performance</h1>
                <p class="lead">For tunnel or trench retention systems</p>
                <p><a class="btn btn-lg btn-success" href="<?php echo Url::to(['site/login']); ?>">Get started with DRAINMAX</a></p>
            </div>
        </div>

        <div class="jumbotron">
            <h2>Connect your INTEWA Products to the Internet of Things</h2>
            <p class="lead">Advanced connection to building management systems available, <a href="<?php echo Url::to(['site/contact']); ?>">contact</a> us!</p>
        </div>





    </div>
</div>