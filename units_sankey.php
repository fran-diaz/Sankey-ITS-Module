<?php
/**
 * Componente text
 */

class units_sankey extends base_component implements components_interface {
	
	public function make_sankey() : string {
		global $_ITE, $_ITEC;
		$html = '';
		ob_start();
		?>

		<section id="org_sankey" class="col12 h-100 position-relative">
            <?php
            // Adaptar a config nueva
            $num_levels = (isset($_GET['num_levels']))?$_GET['num_levels']:'1000';

            $levels = $_ITEC -> select ( "levels", '*', ['levels_id[<=]' => $num_levels, 'ORDER' => ['levels_id' => 'ASC']]);
            if ($levels) {foreach($levels as $level){
                echo '<section class="col12 m-3 p-3 level text-center" data-ref="'.$level['levels_id'].'" data-name="'.$level['level'].'"><span class="level_name" data-ref="'.$level['levels_id'].'" title="'.$_ITE -> lang -> gt('Editar nombre del nivel').'">'.$level['level'].'</span>';
                    $units = $_ITEC -> select( "units", '*', ['levels_id' => $level['levels_id'], 'active' => '1', 'ORDER' => ['unit_name' => 'ASC']] );
                    if($units) {foreach($units as $unit){
                        //if(!$_ITE->auth->unitAllowed($unit['units_id'])){continue;}
                        $parents = $_ITEC -> query("SELECT u.units_id, u.unit_name FROM relationships r, units u WHERE r.units_id = '$unit[units_id]' AND r.parent_id = u.units_id AND DATE(r.end_date) = '2050-01-01' ORDER BY r.relationships_id DESC") -> fetchAll();
                        $relations = "";
                        if($parents){
                            foreach($parents as $parent){
                                $relations .= $parent['units_id'].$parent['unit_name'][0]."|";
                            }
                            $relations = substr($relations, 0, -1);
                        }
                        echo '<div class="box p-3 m-3" data-ref="'.$unit['units_id'].'" data-relationships="'.$relations.'" data-relation-code="'.$unit['units_id'].$unit['unit_name'][0].'"><a href="#" title="'.$_ITE -> lang -> gt('Editar unidad organizativa').'">'.$unit['unit_name'].'</a></div>';
                    }}
                echo '</section>';
            }}
            ?>
        </section>
        <script type="text/javascript">
        	var RAD2DEG = 180 / Math.PI;

        	function draw_sankey(){
			    sankey = $('#org_sankey');
			    levels = sankey.find('section');
			    num_levels = levels.length;
			    
			    // Clear previus lines
			    sankey.find('.line').remove();
			    
			    for(l=0;l<num_levels;l++){
			        level = levels.eq(l);
			        
			        els = level.find('div');
			        num_els = els.length;
			        for(i=0;i<num_els;i++){
			            origin = els.eq(i);
			            relationships = origin.attr('data-relation-code').split('|');
			            next_level = levels.eq(l+1);
			            nl_els = next_level.find('div');
			            num_nl_els = nl_els.length;
			            for(j=0;j<num_nl_els;j++){
			                for(r=0;r<relationships.length;r++){ 
			                    relationship = relationships[r];
			                    if(jQuery.inArray(relationship,nl_els.eq(j).attr('data-relationships').split('|')) !== -1){
			                        destination = nl_els.eq(j);

			                        origin_position = origin.position();
			                        origin_parent = origin.parent()[0];
			                        destination_position = destination.position();
			                        destination_parent = destination.parent()[0];

			                        x1 = origin_position.left + (origin.outerWidth()/2) + origin_parent.offsetLeft;
			                        y1 = origin_position.top + origin.outerHeight() + origin_parent.offsetTop;
			                        x2 = destination_position.left + (destination.outerWidth()/2) + destination_parent.offsetLeft;
			                        y2 = destination_position.top + destination_parent.offsetTop;
			                        dist_x = (x1 - x2) * -1;
			                        dist_y = (y1 - y2) * -1;
			                        degs = (Math.atan2(dist_y, dist_x) * RAD2DEG).toFixed(2);
			                        width = Math.ceil(Math.sqrt(Math.pow(dist_x, 2) + Math.pow(dist_y, 2)));

			                        sankey.append('<div class="line hidden" data-relationship="'+relationship+'" style="top:'+y1+'px;left:'+x1+'px;width:'+width+'px;-webkit-transform: rotate('+degs+'deg);-moz-transform: rotate('+degs+'deg);-o-transform: rotate('+degs+'deg);-ms-transform: rotate('+degs+'deg);transform: rotate('+degs+'deg);"></div>');
			                    }
			                }
			            }
			        }
			    }
			}

			function adapt_sankey_levels_height(){
			    $('#org_sankey > section').each(function(){
			        min_height = $(this).find('span').outerWidth();
			        padding = $(this).innerHeight() - $(this).height();
			        if($(this).innerHeight() < min_height){$(this).height(min_height - padding);}
			        else{$(this).find('span').width($(this).innerHeight() - 20);}
			    });
			}

			draw_sankey();
        </script>
		<?php

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public function gen_content( ) : string {		
		return $this -> make_sankey();
	}
}