<?xml version="1.0"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		version='1.0'>

<!-- doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" -->
  <xsl:output method="html" encoding="utf-8"
	doctype-system="http://www.w3.org/TR/html4/strict.dtd"
	doctype-public="-//W3C//DTD HTML 4.01//EN"/>

  <xsl:param name="file">unknown</xsl:param>

  <xsl:template match='*[name() = "download"]'>
    <a href='http://downloads.sourceforge.net/zero-install/{@name}'><xsl:value-of select='@name'/></a>
  </xsl:template>

  <xsl:template match='item'>
    <!-- Show expanded if we have children and,
         - We are selected
	 - One of our descendants is selected, or
	 - We are a top-level item
    -->
    <xsl:variable name='expand' select='item and ((descendant-or-self::item[concat(@base, ".html") = $file]) or ..=/)'/>

    <li>
      <xsl:choose>
        <xsl:when test='$expand'>
	  <xsl:attribute name='class'>open</xsl:attribute>
        </xsl:when>
        <xsl:when test='item'>
	  <xsl:attribute name='class'>closed</xsl:attribute>
        </xsl:when>
	<xsl:otherwise>
	  <xsl:attribute name='class'>leaf</xsl:attribute>
	</xsl:otherwise>
      </xsl:choose>
      <xsl:variable name='href'>
        <xsl:choose>
	  <xsl:when test='@href'><xsl:value-of select='@href'/></xsl:when>
	  <xsl:when test='@base'><xsl:value-of select='@base'/>.html</xsl:when>
	  <xsl:otherwise>Missing base or href</xsl:otherwise>
	</xsl:choose>
      </xsl:variable>
      <a href="{$href}">
      <xsl:if test='$file = concat(@base, ".html")'>
	<xsl:attribute name='class'>selected</xsl:attribute>
      </xsl:if>
        <xsl:value-of select='@label'/>&#160;
      </a>
      <!-- Expanded contents -->
      <xsl:if test='$expand'>
       <ul>
        <xsl:apply-templates select='item'/>
       </ul>
      </xsl:if>
    </li>
  </xsl:template>

  <xsl:template name='make-links'>
    <ul class='pages'>
      <xsl:apply-templates select='document("structure.xml")/layout/item'/>
    </ul>

  </xsl:template>

  <xsl:template match='/*'>
    <html lang='en'>
      <head>
	<!--<link rel="alternate" type="application/rss+xml" title="Thomas Leonard's blog" href="http://roscidus.com/desktop/blog/1/feed" /> -->
        <link rel='stylesheet' type='text/css' href='style.css' />
        <link rel="icon" href="logo/favicon.ico" type="image/vnd.microsoft.icon"/>
        <title>0install: <xsl:value-of select='document("structure.xml")/layout//item[concat(@base, ".html") = $file]/@label'/></title>
      </head>

      <body>
	<table>
	 <tr>
	  <td class='logo'>
	    <a href='/'><img src='logo/zeroinstall-icon.png' width='96' height='95' alt='logo'/></a>
	  </td>
	  <td class='header'>
	    <form id="searchbox_010445122533180311286:4ee7gv0f1pc" action="http://google.com/cse" style='float: right; padding-right: 1em;'>
	     <p>
	      <input type="hidden" name="cx" value="010445122533180311286:4ee7gv0f1pc" />
	      <input name="q" type="search" size="14" />
	      <input type="submit" name="sa" value="Search" />
	      <input type="hidden" name="cof" value="FORID:0" />
	     </p>
	    </form>

	    <h1><a href='/'>Zero Install</a></h1>
	    <p class='tagline'>the antidote to app-stores</p>
	    <!--<p class='summary'>a decentralised cross-distribution software installation system</p> -->
	    <!--<p class='author'>Dr Thomas Leonard <span class='actions'>[ <a href="support.html">contact</a> | <a href="public_key.gpg">GPG public key</a> | <a href="http://roscidus.com/desktop/blog/1">blog</a> | <a href="http://sourceforge.net/developer/user_donations.php?user_id=40461">donations</a> ]</span></p>-->
	  </td>
	 </tr>
	 <tr>
	  <td class='sidebar'>
	   <xsl:call-template name='make-links'/>
	  </td>
          <td class='main'>
	   <div class='main'>
            <xsl:apply-templates/>
	   </div>

	 <!--
	 <xsl:if test='$file != "index.html"'>
	   <form id='commentForm' method='post' action='http://roscidus.com/comments/comment.php' class='comment'>
	     <fieldset>
	       <textarea name='b' rows='2' cols='80'>Your comment here...</textarea><br/>
	       <input type='hidden' name='page' value='{$file}'/>
	       <input type='submit' value='Send feedback'/>
	     </fieldset>
	     <script type='text/javascript'>
		b = document.getElementById("commentForm").b;
		b.onfocus = function () {b.style.color="#000"; if (b.value == "Your comment here...") b.value = "";};
	     </script>
	   </form>
	 </xsl:if>
	 -->
	
        <div class='footer'>
	 <p>
	    Web-site &#169; Copyright 2003-2015 Thomas Leonard and others<br/>
	  </p>
	  <p>
	    Permission is granted to use the site (excluding the software,
	    which is licensed separately)<br/>in accordance with the terms of the
	    <a href="http://creativecommons.org/licenses/by-sa/2.5/">Creative
	    Commons Attribution-ShareAlike 2.5 license</a>.
	  </p>
	  <p>
	    Some of the icons are from (or based on icons from) the
	    <a href='http://tango.freedesktop.org/Tango_Desktop_Project'>Tango Desktop Project</a> and the <a href='http://www.openclipart.org/'>Open Clipart Library</a>.
	  </p>
	  <span class='logos'>
	     <a href="http://creativecommons.org/licenses/by-sa/2.5/">
	      <img src="http://creativecommons.org/images/public/somerights20.gif"
	      	   alt='Attribution-ShareAlike' width='88' height='31'/>
	     </a>
	     <!--
	    <a href="http://jigsaw.w3.org/css-validator/check/referer">
	      <img style="border:0;width:88px;height:31px"
	   		src="http://jigsaw.w3.org/css-validator/images/vcss" 
	    		alt="Valid CSS!"/>
	    </a>
	    -->
	    <!--
	    <a class='outside' href="http://validator.w3.org/check/referer">
	      <img src="http://www.w3.org/Icons/valid-xhtml10"
	    	   alt="Valid XHTML 1.0!" height="31" width="88"/>
	    </a>
	    -->
	    <a href="http://sourceforge.net/projects/zero-install"><img src="http://sflogo.sourceforge.net/sflogo.php?group_id=76468&amp;type=11" width="120" height="30" alt="SourceForge.net. Fast, secure and Free Open Source software downloads" /></a>
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

  <xsl:template match='*[name() = "quicklinks"]'>
    <!-- <xsl:variable name='width'><xsl:value-of select='100 div count(*)'/></xsl:variable> -->
    <div class='quicklinks'>
     <div class='row'>
      <xsl:for-each select='*[name() = "link"]'>
	<a href='{@href}'>
         <img src='{@img}' alt='' width='48' height='48'/><br/>
         <xsl:apply-templates/>
	</a>
      </xsl:for-each>
      <xsl:for-each select='*[name() = "a"]'>
	<xsl:apply-templates select='.'/>
      </xsl:for-each>
     </div>
    </div>
  </xsl:template>

  <xsl:template match='*[name() = "video"]'>
    <object width="425" height="355" type="application/x-shockwave-flash">
      <xsl:attribute name='data'>http://www.youtube.com/v/<xsl:value-of select='@vid'/></xsl:attribute>
      <a>
        <xsl:attribute name='href'>http://www.youtube.com/watch?v=<xsl:value-of select='@vid'/></xsl:attribute>
        <img src='screens/no_video.png' width='425' height='355' alt='Embedded video not supported by your browser'/>
      </a>
    </object>
    <p class='caption'><a>
      <xsl:attribute name='href'>http://www.youtube.com/watch?v=<xsl:value-of select='@vid'/></xsl:attribute><xsl:apply-templates/></a><br/>
      <span class='captionnote'>(screencast with audio commentary)</span>
    </p>
  </xsl:template>

  <xsl:template match='*[name() = "news"]'>
    <dl>
      <xsl:apply-templates select='document("news.xml")/html/div/dl/*[count(preceding::dd) &lt; 5]'/>
    </dl>
  </xsl:template>

  <xsl:template name='contents-entry'>
    <xsl:choose>
      <xsl:when test='@id'>
	<a href="#{@id}"><xsl:value-of select='.'/></a>
      </xsl:when>
      <xsl:otherwise>
	<a href="#{generate-id()}"><xsl:value-of select='.'/></a>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match='*[name() = "pre-scrolled"]'>
    <!-- hack to stop the page width growing -->
    <table class='fixed'>
      <td><pre><xsl:apply-templates/></pre></td>
    </table>
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
                  <li><xsl:call-template name='contents-entry'/></li>
                </xsl:for-each>
                <xsl:for-each select='../*[name() = "dl"]/*[name() = $level]'>
                  <li><xsl:call-template name='contents-entry'/></li>
                </xsl:for-each>
	      </ol>
	    </li>
          </xsl:for-each>
        </ul>
      </xsl:when>
      <xsl:otherwise>
        <ol class='toc'>
        <xsl:for-each select='following::*[name() = $level]'>
          <li><xsl:call-template name='contents-entry'/></li>
        </xsl:for-each>
        </ol>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match='*[name() = "feeds"]'>
    <xsl:variable name='source' select='document(@source)'/>
    <table class='picturebullets' style='margin-left: 2em'>
      <xsl:for-each select='$source/*/feed'>
	<tr class='d{position() mod 2}'>
	  <td class='image'>
	    <img src='{@icon}' alt='' width='{@width}' height='{@height}'/>
	  </td>
	  <td class='keyinfo'><a href='{@uri}'><xsl:value-of select='@name'/></a><br/>
	    <span class='summary'><xsl:value-of select='@summary'/></span><br/>
	  </td>
	  <td class='teaser'>
	    <xsl:value-of select='text()|*'/>
	    <xsl:if test='@homepage'>
	      <a href='{@homepage}'>Home page</a>
	    </xsl:if>
	    <p>Platforms:
	      <xsl:for-each select='archs/arch'>
		<b><xsl:value-of select='@name'/></b>
		<xsl:if test="position() != last()">
		  <xsl:text>, </xsl:text>
		</xsl:if>
	      </xsl:for-each>
	    </p>
	  </td>
	</tr>
      </xsl:for-each>
    </table>
    <!--
    <p>
      Number of feeds: <xsl:value-of select='count($source/*/feed)'/>
    </p>
    -->
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
    <table>
     <tr>
      <td style='vertical-align: top'>
       <h2><xsl:choose><xsl:when test='@title'><xsl:value-of select='@title'/></xsl:when><xsl:otherwise><xsl:value-of select='@name'/></xsl:otherwise></xsl:choose></h2>
       <xsl:apply-templates/>
      </td>
      <td style='vertical-align: top'>
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
      </td>
     </tr>
    </table>
  </xsl:template>

  <xsl:template match='*[name() = "h3" or name() = "h2" or name() = "dt"]'>
    <xsl:copy>
      <xsl:attribute name='id'><xsl:value-of select="generate-id()"/></xsl:attribute>
      <xsl:apply-templates select='*|text()|@*'/>
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
