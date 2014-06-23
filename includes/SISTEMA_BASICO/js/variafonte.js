// JavaScript Document
// JavaScript Document
/*var tgs = new Array( 'p' ); //pega todas as tags p//

//Specify spectrum of different font sizes:
var szs = new Array( '1em','1.1em','1.2em','1.3em','1.4em','1.5em','1.6em' );
var startSz = 0;

function ts( trgt,inc ) {
if (!document.getElementById) return
var d = document,cEl = null,sz = startSz,i,j,cTags;
sz += inc;
if ( sz < 0 ) sz = 0;
if ( sz > 6 ) sz = 6;
startSz = sz;
if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];

cEl.style.fontSize = szs[ sz ];

for ( i = 0; i < tgs.length; i++ ) {
cTags = cEl.getElementsByTagName( tgs[ i ] );
for ( j = 0; j < cTags.length; j++ ) cTags[ j ].style.fontSize = szs[ sz ];
}
}*/



/*var tgs = new Array( 'p','li' );
var szs = new Array( '12pt','14pt','16pt','18pt','20pt' );
var startSz = 0;

function ts( trgt,inc ) {
        if (!document.getElementById) return
        var d = document,cEl = null,sz = startSz,i,j,cTags;

    sz += inc;
        if ( sz < 0 ) sz = 0;
        if ( sz > 10 ) sz = 10;
        startSz = sz;

        if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];

cEl.style.fontSize = szs[ sz ];

for ( i = 0 ; i < tgs.length ; i++ ) {
cTags = cEl.getElementsByTagName( tgs[ i ] );

for ( j = 0 ; j < cTags.length ; j++ ) cTags[ j ].style.fontSize = szs[ sz ];
        }
        }*/



var tgs = new Array();
var szs = new Array( '10pt','11pt','12pt','13pt','14pt','16pt','18pt' );
var startSz = 0;

function ts( trgt,inc ) {
        if (!document.getElementById) return
        var d = document,cEl = null,sz = startSz,i,j,cTags;

    sz += inc;
        if ( sz < 0 ) sz = 0;
        if ( sz > 4 ) sz = 4;
        startSz = sz;

        if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];

cEl.style.fontSize = szs[ sz ];

for ( i = 0 ; i < tgs.length ; i++ ) {
cTags = cEl.getElementsByTagName( tgs[ i ] );

for ( j = 0 ; j < cTags.length ; j++ ) cTags[ j ].style.fontSize = szs[ sz ];
        }
        }
