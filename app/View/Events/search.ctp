<style>
    legend{
        font-size: 17px;
        margin-bottom: 0px;
        font-family: sans-serif;
        line-height: 30px;
    } 
    
    p{
        font-family: sans-serif;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    
    #div_result:hover blockquote{
        color: #999;
    }
    
    .control-group{
        margin-bottom: 0px;
    }
    
    .span12{
        margin-bottom: 5px;
    }
    
    
</style>
<div class="container">
    
<div class="span8" style="float:none; margin-right:auto; margin-left:auto;">
    <div class="text-center" style="padding:5px 70px 0px 70px; background: rgba(0, 0, 0, 0.3); border-radius: 5px; color: White;"> 
            <div class="bs-docs-grid" >
                <div class="row-fluid show-grid">
                        <legend style="color: #999;">Filtre</legend>
                </div>
                <div class="row-fluid show-grid">
                        <div class="control-group">
                            <div class="controls" style="padding:10px;">
                                <form action="/Agenda/Events/search"  method="POST" id="form_search">
                                    <input type="hidden" value="" name="search" id="search" value="<?= isset($search) ? $search : null ?>">
                                    <input readonly type="text" value="<?= isset($date_debut) ?  $date_debut : null?>" class='form_datetime text-center' placeholder="Date début" name="date_debut" id="date_debut">  - 
                                    <input readonly type="text" value="<?= isset($date_fin) ?  $date_fin : null ?>" class='form_datetime text-center' placeholder="Date fin" name="date_fin" id="date_fin">
                                </form>
                                <div class="alert alert-error" id="alert" style="display: none;">
                                    <strong></strong>
                                </div>
                            </div>
                        </div>
                </div>
               
            </div>
        </div>
        </a>
    </div>
    
    <br>
    <?php
    if(isset($ListEvents)){
    foreach ($ListEvents as $Event) {
    ?>
    <div id="div_result" class="span12 event_brillant" style="float:none; margin-right:auto; margin-left:auto;">
        <a href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'index'))."#".$Event['Event']['id'];?>">
        <div style="padding:5px 0px 0px 50px; background: rgba(999, 999, 999, 0.3); border-radius: 5px; color: White;"> 
            <div class="bs-docs-grid">
                <div class="row-fluid show-grid">
                    <div class="span10">
                        <legend><strong><?= $Event['Event']['title'] ?></strong>
                        
                        <?php      
                        $startdate = date("l d F y", strtotime($Event['Event']['date_start']));
                        $enddate= date("l d F y", strtotime($Event['Event']['date_end']));

                        $starttime = date("H:i", strtotime($Event['Event']['date_start']));
                        $endtime= date("H:i", strtotime($Event['Event']['date_end']));

                        if($Event['Event']['allday']){
                            if ($startdate == $enddate){
                                echo "(Le ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_start']))).")";
                            }else{
                                echo  "(Du ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_start'])))." au ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_end']))).")";
                            }
                        }else{
                            if ($startdate == $enddate){
                                echo "(Le ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_start'])))." de ".$starttime." à ".$endtime.")";
                            }else{
                                echo "(Du ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_start'])))." à ".$starttime." au ".utf8_encode(strftime("%d %B %Y",strtotime($Event['Event']['date_end'])))." à ".$endtime.")";
                            }
                        }                                                      
                        ?>   
                        </legend>
                    </div>
                </div>
                <div class="row-fluid show-grid">
                        <div class="control-group">
                            <div class="controls">
                                <blockquote> 
                                    <p style="font-size: 14px"><?= $Event['Event']['description'] ?></p>
                                    <small style="font-size: 11px;"><?=  implode(", ", $lst_participants[$Event['Event']['id']]) ?></small>
                                </blockquote>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <h6></h6>
                            </div>
                        </div>
                </div>
               
            </div>
        </div>
        </a>
    </div>
     <?php }} ?>
    
</div>

<script type="text/javascript">
    var startDate;
    var endDate;
    var checkin = $('#date_debut').datepicker({format: "dd-mm-yyyy"})
        .on('changeDate', function(ev){
            if (typeof endDate !== 'undefined'){
                if (ev.date.valueOf() > endDate.valueOf()){
                        $('#alert').show().find('strong').text('La date de fin doit être inferieur à la date de début');
                }else{
                    $('#alert').hide();
                }
            }
            startDate = new Date(ev.date);
            $('#date_debut').datepicker('hide');
    });
    var checkout =$('#date_fin').datepicker({format: "dd-mm-yyyy"})
    .on('changeDate', function(ev){
        if (typeof startDate !== 'undefined'){
                    if (ev.date.valueOf() < startDate.valueOf()){
                            $('#alert').show().find('strong').text('La date de fin doit être inferieur à la date de début');
                    } else {
                            $('#alert').hide();
                            $("#form_search").submit();
                    }
        }
        endDate = new Date(ev.date);
        $('#date_fin').datepicker('hide');
    });
                                
</script>

