function EscreveFlash(piWid, piHei, psSrc, psId) 
{
var strSwf;

strSwf = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" id="+psId+" width="+piWid+" height="+piHei+">";
strSwf += "<param name=\"movie\" value="+psSrc+" />";
strSwf += "<param name=\"allowScriptAccess\" value=\"sameDomain\" />";
strSwf += "<param name=\"quality\" value=\"high\" />";
strSwf += "<param name=\"wmode\" value=\"transparent\" />";
strSwf += "<embed id="+psId+" name="+psId+" allowScriptAccess=\"sameDomain\" swLiveConnect=\"true\" src="+psSrc+" quality=\"high\" wmode=\"transparent\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width="+piWid+" height="+piHei+"></embed></object>";

this.document.write(strSwf);
}

/*<script language="javascript" src="escreve_flash.js"></script>
<script> EscreveFlash(733, 110, "topo.swf"); </script>*/