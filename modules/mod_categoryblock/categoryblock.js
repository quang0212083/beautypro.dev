var cbmodarray= new Array();
function CBStartAllModules()
{
	for(i=0; i<cbmodarray.length;i++)
	{
		setTimeout(cbmodarray[i], 1000+1000*i);
	}
}
window.onload = CBStartAllModules;