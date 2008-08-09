<?xml version="1.0"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version='1.0'
		xmlns='http://www.w3.org/1999/xhtml'>

<!-- doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" -->
  <xsl:output method="xml" encoding="utf-8"
	doctype-system="/usr/share/4Suite/Schemata/xhtml1-strict.dtd"
	doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

  <xsl:param name="file">unknown</xsl:param>

  <xsl:template match='*[name() = "download"]'>
    <a href='http://downloads.sourceforge.net/zero-install/{@name}'><xsl:value-of select='@name'/></a>
  </xsl:template>

  <xsl:template match='item'>
    <li>
      <xsl:choose>
        <xsl:when test='item and (../descendant-or-self::item[concat(@base, ".html") = $file])'>
	  <xsl:attribute name='class'>open</xsl:attribute>
        </xsl:when>
        <xsl:when test='item'>
	  <xsl:attribute name='class'>closed</xsl:attribute>
        </xsl:when>
	<xsl:otherwise>
	  <xsl:attribute name='class'>leaf</xsl:attribute>
	</xsl:otherwise>
      </xsl:choose>
      <a href="{@base}.html">
      <xsl:if test='$file = concat(@base, ".html")'>
	<xsl:attribute name='class'>selected</xsl:attribute>
      </xsl:if>
        <xsl:value-of select='@label'/>&#160;
      </a>
      <!-- Expanded contents -->
      <xsl:if test='item and (../descendant-or-self::item[concat(@base, ".html") = $file])'>
       <ul>
        <xsl:apply-templates select='item'/>
       </ul>
      </xsl:if>
    </li>
  </xsl:template>

  <xsl:template name='make-links'>
    <h2>Navigation</h2>
    <ul class='pages'>
      <xsl:apply-templates select='document("structure.xml")/layout/item'/>
    </ul>

    <form id="searchbox_010445122533180311286:4ee7gv0f1pc" action="http://google.com/cse">
     <p>
      <input type="hidden" name="cx" value="010445122533180311286:4ee7gv0f1pc" />
      <input name="q" type="text" size="18" />
      <input type="submit" name="sa" value="Search" />
      <input type="hidden" name="cof" value="FORID:0" />
     </p>
    </form>

    <h2>SourceForge</h2>
    <ul>
     <li class='leaf'><a href='http://sourceforge.net/projects/zero-install'>Project page</a></li>
     <li class='leaf'><a href='http://sourceforge.net/svn/?group_id=76468'>Subversion access</a></li>
     <li class='leaf'><a href='http://sourceforge.net/project/showfiles.php?group_id=76468'>File releases</a></li>
     <li class='leaf'><a href='http://sourceforge.net/project/stats/detail.php?group_id=76468&amp;ugn=zero-install&amp;type=prweb&amp;mode=alltime'>Statistics</a></li>
    </ul>
  </xsl:template>

  <xsl:template match='/*'>
    <html xml:lang='en' lang='en'>
      <head>
        <link rel="alternate" type="application/rss+xml" title="Thomas Leonard's blog" href="http://roscidus.com/desktop/blog/1/feed" />
        <link rel='stylesheet' type='text/css' href='style.css' />
        <title>Zero Install: <xsl:value-of select='document("structure.xml")/layout//item[concat(@base, ".html") = $file]/@label'/></title>
      </head>

      <body>
	<h1>The Zero Install system</h1>
	<table>
	 <tr>
	  <td class='sidebar'>
	   <xsl:call-template name='make-links'/>
	  </td>
          <td class='main'>
            <p class='author'>Dr Thomas Leonard [ <a href="support.html">contact</a> | <a href="public_key.gpg">GPG public key</a> | <a href="http://roscidus.com/desktop/blog/1">blog</a> | <a href="http://sourceforge.net/developer/user_donations.php?user_id=40461">donations</a> ]</p>
	   <div class='main'>
            <xsl:apply-templates/>
	   </div>
	
        <div class='footer'>
	 <p>
	    Web-site &#169; Copyright 2003-2008 by Thomas Leonard<br/>
	  </p>
	  <p>
	    Permission is granted to use the site (excluding the software,
	    which is licensed separately)<br/>in accordance with the terms of the
	    <a href="http://creativecommons.org/licenses/by-sa/2.5/">Creative
	    Commons Attribution-ShareAlike 2.5 license</a>.
	  </p>
	  <p>
	    Thanks to the University of Southampton for the 0install.net domain name!<br/>
	    Some of the icons are from (or based on icons from) the
	    <a href='http://tango-project.org/Tango_Desktop_Project'>Tango Desktop Project</a>.
	  </p>
	  <span class='logos'>
	    <a href="http://sourceforge.net/projects/zero-install">
	      <img width="88" height="31" alt="SF logo"
     	       src="http://sourceforge.net/sflogo.php?group_id=7023&amp;type=1"/>
	    </a>
	     <a href="http://creativecommons.org/licenses/by-sa/2.5/">
	      <img src="http://creativecommons.org/images/public/somerights20.gif"
	      	   alt='Attribution-ShareAlike' width='88' height='31'/>
	     </a>
	    <a href="http://jigsaw.w3.org/css-validator/check/referer">
	      <img style="border:0;width:88px;height:31px"
	   		src="http://jigsaw.w3.org/css-validator/images/vcss" 
	    		alt="Valid CSS!"/>
	    </a>
	    <a class='outside' href="http://validator.w3.org/check/referer">
	      <img src="http://www.w3.org/Icons/valid-xhtml10"
	    	   alt="Valid XHTML 1.0!" height="31" width="88"/>
	    </a>
	  </span>
	</div>
	  </td>
	 </tr>
        </table>
      </body>
    </html>
  </xsl:template>
  
  <xsl:template match='@*|node()'>
    <xsl:copy>
      <xsl:apply-templates select='@*|node()'/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match='*[name() = "no"]'>
    <td class='no'>
      <xsl:choose>
        <xsl:when test='text()'><xsl:apply-templates select='@*|node()'/></xsl:when>
	<xsl:otherwise>No</xsl:otherwise>
      </xsl:choose>
    </td>
  </xsl:template>

  <xsl:template match='*[name() = "yes"]'>
    <td class='yes'>
      <xsl:choose>
        <xsl:when test='text()|*'><xsl:apply-templates select='@*|node()'/></xsl:when>
	<xsl:otherwise>Yes</xsl:otherwise>
      </xsl:choose>
    </td>
  </xsl:template>

  <xsl:template match='*[name() = "video"]'>
    <object width="425" height="355" type="application/x-shockwave-flash">
      <xsl:attribute name='data'>http://www.youtube.com/v/<xsl:value-of select='@vid'/></xsl:attribute>
      <a>
        <xsl:attribute name='href'>http://www.youtube.com/watch?v=<xsl:value-of select='@vid'/></xsl:attribute>
        <img src='screens/no_video.png' width='425' height='355' alt='Embedded video not supported by your browser'/>
      </a>
    </object>
    <p><a>
      <xsl:attribute name='href'>http://www.youtube.com/watch?v=<xsl:value-of select='@vid'/></xsl:attribute><xsl:apply-templates/></a> (screencast with audio commentary)
    </p>
  </xsl:template>

  <xsl:template match='*[name() = "toc"]'>
    <xsl:variable name='level'><xsl:value-of select='@level'/></xsl:variable>

    <xsl:choose>
      <xsl:when test='@super-level'>
	<xsl:variable name='superlevel'><xsl:value-of select='@super-level'/></xsl:variable>
        <ul class='faqtoc'>
          <xsl:for-each select='following::*[name() = $superlevel]'>
            <li><xsl:value-of select='.'/>
	      <ol>
                <xsl:for-each select='../*[name() = $level]'>
                  <li><a href="#{generate-id()}"><xsl:value-of select='.'/></a></li>
                </xsl:for-each>
	      </ol>
	    </li>
          </xsl:for-each>
        </ul>
      </xsl:when>
      <xsl:otherwise>
        <ol>
        <xsl:for-each select='following::*[name() = $level]'>
          <li><a href="#{generate-id()}"><xsl:value-of select='.'/></a></li>
        </xsl:for-each>
        </ol>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match='*[name() = "feeds"]'>
     <h2>Contents</h2>
     <ol>
       <xsl:for-each select='document("all-feeds.xml")/*/category'>
         <xsl:variable name='category'><xsl:value-of select='@name'/></xsl:variable>
         <li><a href='#cat-{@name}'><xsl:value-of select='@name'/></a> <span class='count'>(<xsl:value-of select='count(document("all-feeds.xml")/*/feed[@category=$category])'/>)</span></li>
       </xsl:for-each>
     </ol>

     <xsl:for-each select='document("all-feeds.xml")/*/category'>
       <xsl:variable name='category'><xsl:value-of select='@name'/></xsl:variable>
       <h2 id='cat-{$category}'><xsl:value-of select='$category'/></h2>
       <table class='picturebullets' style='margin-left: 2em'>
        <xsl:for-each select='document("all-feeds.xml")/*/feed[@category=$category]'>
         <tr class='d{position() mod 2}'>
          <td class='image'>
	   <img src='{@icon}' alt='' width='{@width}' height='{@height}'/>
          </td>
          <td class='keyinfo'><a href='{@uri}'><xsl:value-of select='@name'/></a><br/>
           <span class='summary'><xsl:value-of select='@summary'/></span><br/>
          </td>
	  <td class='teaser'>
	    <xsl:value-of select='.'/>
	    <xsl:if test='@homepage'>
              <a href='{@homepage}'>Home page</a>
	    </xsl:if>
	  </td>
         </tr>
        </xsl:for-each>
      </table>
     </xsl:for-each>
    <p>
      Number of feeds: <xsl:value-of select='count(document("all-feeds.xml")/*/feed)'/>
    </p>
  </xsl:template>

  <xsl:template match='*[name() = "security"]'>
    <div class='security'>
      <h4>Security note:</h4>
      <xsl:apply-templates select='node()'/>
    </div>
  </xsl:template>

  <xsl:template match='*[name() = "k"]'>
    <span class='keyphrase'><xsl:apply-templates select='node()'/></span>
  </xsl:template>

  <xsl:template match='*[name() = "more"]'>
    <span class='more'>[&#160;<a><xsl:apply-templates select='@*'/>more</a>&#160;]</span>
  </xsl:template>

  <xsl:template match='*[name() = "program"]'>
    <div class='program'>
      <table>
        <tr><th>Name</th><td><xsl:value-of select='@name'/></td></tr>
        <tr><th>Maintainer</th><td><xsl:value-of select='@author'/></td></tr>
        <tr><th>License</th><td><xsl:value-of select='@license'/></td></tr>
	<xsl:if test='@feed'>
	  <tr><th>Run it</th><td><a href='{@feed}'>Zero Install feed</a></td></tr>
	</xsl:if>
	<xsl:if test='@git'>
	  <tr><th>SCM</th><td><a href='{@git}'>GIT repository</a></td></tr>
	</xsl:if>
	<xsl:if test='@svn'>
	  <tr><th>Subversion</th><td><a href='{@svn}'>Trunk</a></td></tr>
	</xsl:if>
      </table>
    </div>
  </xsl:template>

  <xsl:template match='*[name() = "h3" or name() = "h2"]'>
    <xsl:copy>
      <xsl:attribute name='id'><xsl:value-of select="generate-id()"/></xsl:attribute>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
