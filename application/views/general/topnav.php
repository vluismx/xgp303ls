<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<div id='header_top'><center>
<table class='header'>
<tr class='header' >
<td class='header' style='width:5;' >
	  <table class='header'>
    <tr class='header'>
     <td class='header'><img src="{dpath}planets/small/s_{image}.jpg" height="50" width="50"></td>
     <td class='header'>
	  <table class='header'>

                    <select size="1" onChange="eval('location=\''+this.options[this.selectedIndex].value+'\'');">
                    {planetlist}
                    </select>
	  </table>
     </td>
    </tr>
  </table></td>
<td class='header'>   <table class='header' id='resources' border="0" cellspacing="0" cellpadding="0" padding-right='30' >

	    <tr class='header'>

		     <td align="center" width="85" class='header'>
		      <img border="0" src="{dpath}resources/metal.gif" width="42" height="22">
		     </td>

		     <td align="center" width="85" class='header'>
		      <img border="0" src="{dpath}resources/cristal.gif" width="42" height="22">
		     </td>

		     <td align="center" width="85" class='header'>
		      <img border="0" src="{dpath}resources/deuterium.gif" width="42" height="22">
		     </td>

		     <td align="center" width="85" class='header'>
		      <img border="0" src="{dpath}resources/darkmatter.jpg" width="42" height="22" title="Dark Matter">
		     </td>

		     <td align="center" width="85" class='header'>
		      <img border="0" src="{dpath}resources/energy.gif" width="42" height="22">
		     </td>
	    </tr>

	    <tr class='header'>
		    <td align="center" class='header' width="85"><i><b><font color="#ffffff">{Metal}</font></b></i></td>
		    <td align="center" class='header' width="85"><i><b><font color="#ffffff">{Crystal}</font></b></i></td>
		    <td align="center" class='header' width="85"><i><b><font color="#ffffff">{Deuterium}</font></b></i></td>
		    <td align="center" class='header' width="85"><i><b><font color="#ffffff">{Darkmatter}</font></b></i></td>
		    <td align="center" class='header' width="85"><i><b><font color="#ffffff">{Energy}</font></b></i></td>
	    </tr>
	    <tr class='header'>
	     	<td align="center" class='header' width="90"><font ><div id="metal"></div></font></td>
	     	<td align="center" class='header' width="90"><font ><div id="crystal"></div></font></td>
	     	<td align="center" class='header' width="90"><font ><div id="deut"></div></font></td>
	     	<td align="center" class='header' width="90"><font color="#FFFFFF">{darkmatter}</font></DIV></td>
	     	<td align="center" class='header' width="90">{energy}</td>
	    </tr>
        <tr class='header'>
            <td align="center" class='header' width="90"><font color="#00FF00">{metal_max}</font></td>
            <td align="center" class='header' width="90"><font color="#00FF00">{crystal_max}</font></td>
            <td align="center" class='header' width="90"><font color="#00FF00">{deuterium_max}</font></td>
            <td align="center" class='header' width="90"></td>
            <td align="center" class='header' width="90"></td>
        </tr>
   </table></td>
	<td class='header'>
<table class='header' align=left>
	 <tr class='header'>


	 <td align="center" width="35" class='header'>
	     <a href='game.php?page=officier' accesskey="o">
	  <img border="0" src="{dpath}premium/commander_ikon{img_commander}.gif" width="32" height="32" alt="{of_commander}"
		onmouseover="return overlib('<center><font size=1 color=white><b><br>{of_commander}</font><br><br><a href=game.php?page=officier><font size=1 color=lime>{of_get_know}</b></font></a></center>', LEFT, WIDTH, 150);" onmouseout="return nd();">
	  </a>
	 </td>
	 <td align="center" width="35" class='header'>
	  <a href='game.php?page=officier' accesskey="o">
	  <img border="0" src="{dpath}premium/admiral_ikon{img_admiral}.gif" width="32" height="32" alt="{of_admiral}"
		onmouseover="return overlib('<center><font size=1 color=white><b><br>{of_admiral}</font><br><font size=1 color=skyblue>&amp;nbsp;{of_add_admiral}</font><br><br><a href=game.php?page=officier><font size=1 color=lime>{of_get_know}</b></font></a></center>', LEFT, WIDTH, 150);" onmouseout="return nd();">

	  </a>
	 </td>
	 <td align="center" width="35" class='header'>
	  <a href='game.php?page=officier' accesskey="o">
	  <img border="0" src="{dpath}premium/ingenieur_ikon{img_engineer}.gif" width="32" height="32" alt="{of_engineer}"
		onmouseover="return overlib('<center><font size=1 color=white><b><br>{of_engineer}</font><br><font size=1 color=skyblue>{of_add_engineer}</font><br><br><a href=game.php?page=officier><font size=1 color=lime>{of_get_know}</b></font></a></center>', LEFT, WIDTH, 150);" onmouseout="return nd();">
	  </a>
	 </td>
	 <td align="center" width="35" class='header'>
	  <a href='game.php?page=officier' accesskey="o">
	  <img border="0" src="{dpath}premium/geologe_ikon{img_geologist}.gif" width="32" height="32" alt="{of_geologist}"
		onmouseover="return overlib('<center><font size=1 color=white><b><br>{of_geologist}</font><br><font size=1 color=skyblue>{of_add_geologist}</font><br><br><a href=game.php?page=officier><font size=1 color=lime>{of_get_know}</b></font></a></center>', LEFT, WIDTH, 150);" onmouseout="return nd();">
	  </a>
	 </td>
	 <td align="center" width="35" class='header'>
	  <a href='game.php?page=officier' accesskey="o">
	  <img border="0" src="{dpath}premium/technokrat_ikon{img_technocrat}.gif" width="32" height="32" alt="{of_technocrat}" 
		onmouseover="return overlib('<center><font size=1 color=white><b><br>{of_technocrat}</font><br><font size=1 color=skyblue>{of_add_technocrat}</font><br><br><a href=game.php?page=officier><font size=1 color=lime>{of_get_know}</b></font></a></center>', LEFT, WIDTH, 150);" onmouseout="return nd();">
	  </a>
	 </td>
	 <td align="center" class='header'></td>
	</tr>
</table></td>
</tr>
</tr>
</table>
{show_umod_notice}
</div>

<script LANGUAGE='JavaScript'>
    <!--
    var now = new Date();
    var event = new Date();
    var seconds = (Date.parse(now) - Date.parse(event)) / 1000;
    var val = 0;
    var val2 = 0;
    var val3 = 0;

    update();

    function update() {
        now = new Date();
        seconds = (Date.parse(now) - Date.parse(event)) / 1000;

        val = (( {metal_perhour} / 3600) * seconds) + {metalh};
        if( val >= {metal_mmax} ) val = {metalh};
        document.getElementById('metal').innerHTML = number_format( val ,0);
    
        val = ( {crystal_perhour} / 3600) * seconds + {crystalh};
        if( val >= {crystal_mmax} ) val = {crystalh};
        document.getElementById('crystal').innerHTML = number_format( val ,0);

        val = ( {deuterium_perhour} / 3600) * seconds + {deuteriumh};
        if( val >= {deuterium_mmax} ) val = {deuteriumh};
        document.getElementById('deut').innerHTML = number_format( val ,0);


        ID=window.setTimeout('update();',1000);
    }

    function number_format(number,laenge) {
        number = Math.round( number * Math.pow(10, laenge) ) / Math.pow(10, laenge);
        str_number = number+'';
        arr_int = str_number.split('.');
        if(!arr_int[0]) arr_int[0] = '0';
        if(!arr_int[1]) arr_int[1] = '';
        if(arr_int[1].length < laenge){
            nachkomma = arr_int[1];
            for(i=arr_int[1].length+1; i <= laenge; i++){  nachkomma += '0';  }
            arr_int[1] = nachkomma;
        }
        if(arr_int[0].length > 3){
            Begriff = arr_int[0];
            arr_int[0] = '';
            for(j = 3; j < Begriff.length ; j+=3){
            Extrakt = Begriff.slice(Begriff.length - j, Begriff.length - j + 3);
            arr_int[0] = '.' + Extrakt +  arr_int[0] + '';
            }
            str_first = Begriff.substr(0, (Begriff.length % 3 == 0)?3:(Begriff.length % 3));
        arr_int[0] = str_first + arr_int[0];
        }
        return arr_int[0]+''+arr_int[1];
    }
// --></script>