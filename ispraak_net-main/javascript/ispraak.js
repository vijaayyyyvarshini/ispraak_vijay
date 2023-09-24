eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('3(7.X){7["R"+a]=a;7["z"+a]=6(){7["R"+a](7.1k)};7.X("1e",7["z"+a])}E{7.19("z",a,15)}2 j=H V();6 a(){2 e=q.1d("1a");3(e){o(e,"P");2 N=B(q,"*","14");3((e.12<=10)||(N=="")){c(e,"P",d)}}4=B(q,"*","1n");k(i=0;i<4.b;i++){3(4[i].F=="1g"||4[i].F=="1f"||4[i].F=="1c"){4[i].1b=6(){r();c(v.5.5,"f",d)};4[i].O=6(){r();c(v.5.5,"f",d)};j.D(j.b,0,4[i])}E{4[i].O=6(){r();c(v.5.5,"f",d)};4[i].18=6(){o(v.5.5,"f")}}}2 C=17.16.13();2 A=q.M("11");3(C.K("J")+1){c(A[0],"J",d)}3(C.K("I")+1){c(A[0],"I",d)}}6 r(){k(2 i=0;i<j.b;i++){o(j[i].5.5,"f")}}6 B(m,y,w){2 x=(y=="*"&&m.Y)?m.Y:m.M(y);2 G=H V();w=w.1m(/\\-/g,"\\\\-");2 L=H 1l("(^|\\\\s)"+w+"(\\\\s|$)");2 n;k(2 i=0;i<x.b;i++){n=x[i];3(L.1j(n.8)){G.1i(n)}}1h(G)}6 o(p,T){3(p.8){2 h=p.8.Z(" ");2 U=T.t();k(2 i=0;i<h.b;i++){3(h[i].t()==U){h.D(i,1);i--}}p.8=h.S(" ")}}6 c(l,u,Q){3(l.8){2 9=l.8.Z(" ");3(Q){2 W=u.t();k(2 i=0;i<9.b;i++){3(9[i].t()==W){9.D(i,1);i--}}}9[9.b]=u;l.8=9.S(" ")}E{l.8=u}}',62,86,'||var|if|elements|parentNode|function|window|className|_16|initialize|length|addClassName|true|_1|highlighted||_10||el_array|for|_13|_6|_c|removeClassName|_e|document|safari_reset||toUpperCase|_14|this|_8|_9|_7|load|_4|getElementsByClassName|_3|splice|else|type|_a|new|firefox|safari|indexOf|_b|getElementsByTagName|_2|onfocus|no_guidelines|_15|event_load|join|_f|_11|Array|_17|attachEvent|all|split|450|body|offsetWidth|toLowerCase|guidelines|false|userAgent|navigator|onblur|addEventListener|main_body|onclick|file|getElementById|onload|radio|checkbox|return|push|test|event|RegExp|replace|element'.split('|'),0,{}))

function openNav() {
  document.getElementById("mySidebar").style.width = "200px";
  document.getElementById("mainz").style.marginLeft = "50px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("mainz").style.marginLeft= "0";
}



function tdirect() {
    if (document.getElementById("element_3").value != "ar" && document.getElementById("element_3").value !="he" && document.getElementById("element_3").value !="fa" && document.getElementById("element_3").value !="ur")
	{
    document.getElementById("element_2").style.textAlign = "left";
	}     
    else
    {
    document.getElementById("element_2").style.textAlign = "right";
    }        
    
    if (!('webkitSpeechRecognition' in window)) 
	{
	document.getElementById("alert").style.display = "block";
	}

    
    
    
    		if (document.getElementById("element_3").value == "ca" || document.getElementById("element_3").value == "hr" || document.getElementById("element_3").value == "cs" || document.getElementById("element_3").value == "nl")
			{
    			document.getElementById("guide_3").innerHTML = "Select your language of instruction. iSpraak now supports 35 languages! ";
    		}
    		if (document.getElementById("element_3").value == "fr" || document.getElementById("element_3").value == "de" || document.getElementById("element_3").value == "el" || document.getElementById("element_3").value == "he")
			{
    			document.getElementById("guide_3").innerHTML = "Select your language of instruction. iSpraak now supports 35 languages! ";
    		}
    		if (document.getElementById("element_3").value == "hi" || document.getElementById("element_3").value == "ja" || document.getElementById("element_3").value == "ko" || document.getElementById("element_3").value == "pl")
			{
    			document.getElementById("guide_3").innerHTML = "Select your language of instruction. iSpraak now supports 35 languages! ";
    		}
    		if (document.getElementById("element_3").value == "ru" || document.getElementById("element_3").value == "sv" || document.getElementById("element_3").value == "tr" || document.getElementById("element_3").value == "vi")
			{
    			document.getElementById("guide_3").innerHTML = "Select your language of instruction. iSpraak now supports 35 languages! ";
    		}
    		if (document.getElementById("element_3").value == "ar")
			{
    			document.getElementById("guide_3").innerHTML = "Arabic has 13 regional dialects supported!";
    		}
        	if (document.getElementById("element_3").value == "zh")
			{
    			document.getElementById("guide_3").innerHTML = "Chinese has 4 regional dialects supported!";
    		}
			if (document.getElementById("element_3").value == "en")
			{
    			document.getElementById("guide_3").innerHTML = "English has 4 regional dialects supported!";
    		}
    		if (document.getElementById("element_3").value == "it")
			{
    			document.getElementById("guide_3").innerHTML = "Italian has 2 regional dialects supported!";
    		}
    		if (document.getElementById("element_3").value == "pt")
			{
    			document.getElementById("guide_3").innerHTML = "Portuguese has 2 regional dialects supported!";
    		}
        	if (document.getElementById("element_3").value == "es")
			{
    			document.getElementById("guide_3").innerHTML = "Spanish has 20 regional dialects supported!";
    		}
    		if (document.getElementById("element_3").value == "sw")
			{
    			document.getElementById("guide_3").innerHTML = "Swahili has 2 regional dialects supported! (No TTS)";
    		}
    
        		if (document.getElementById("element_3").value == "zu")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Zulu";
    		}
    		
    		    		if (document.getElementById("element_3").value == "am")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Amharic";
    		}
    		
    			    		if (document.getElementById("element_3").value == "hr")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Croatian";
    		}
    		
    				    		if (document.getElementById("element_3").value == "he")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Hebrew";
    		}
    
    
    			    		if (document.getElementById("element_3").value == "ur")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Urdu";
    		}
    
    		    		if (document.getElementById("element_3").value == "ro")
			{
    			document.getElementById("guide_3").innerHTML = "No TTS available for Romanian";
    		}
    
    
    
}




       function myFunction5()
		{
		
     	document.getElementById("alert").style.display = "block";
     }
     
     