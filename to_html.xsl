<?xml version="1.0"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version='1.0'
		xmlns='http://www.w3.org/1999/xhtml'>

<!-- doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" -->
  <xsl:output method="xml" encoding="utf-8"
	doctype-system="/usr/share/4Suite/Schemata/xhtml1-strict.dtd"
	doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

  <xsl:param name="file">unknown</xsl:param>

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
    <h2>SourceForge</h2>
    <ul>
     <li class='leaf'><a href='http://sourceforge.net/projects/zero-install'>Project page</a></li>
     <li class='leaf'><a href='http://sourceforge.net/svn/?group_id=76468'>Subversion access</a></li>
     <li class='leaf'><a href='http://sourceforge.net/project/showfiles.php?group_id=76468'>File releases</a></li>
    </ul>
  </xsl:template>

  <xsl:template match='/*'>
    <html xml:lang='en' lang='en'>
      <head>
        <link rel='stylesheet' type='text/css' href='style.css' />
        <title>The Zero Install system</title>
      </head>

      <body>
	<h1>The Zero Install system</h1>
	<table>
	 <tr>
	  <td class='sidebar'>
	   <xsl:call-template name='make-links'/>
	  </td>
          <td class='main'>
            <p class='author'>Dr Thomas Leonard [ <a href="support.html">contact</a> | <a href="public_key.gpg">GPG public key</a> | <a href="http://rox.sourceforge.net/desktop/blog/1">blog</a> | <a href="http://sourceforge.net/developer/user_donations.php?user_id=40461">donations</a> ]</p>
	   <div class='main'>
            <xsl:apply-templates/>
	   </div>
	
        <div class='footer'>
	 <p>
	   Thanks to the University of Southampton for the 0install.org, 0install.net,
	   zero-install.org and zero-install.net domain names!
	 </p>
	 <p>
	    Web-site &#169; Copyright 2005, Thomas Leonard.<br/>
	    Permission is granted to use the site (excluding the software,
	    which is licensed separately)<br/>in accordance with the terms of the
	    <a href="http://creativecommons.org/licenses/by-sa/2.5/">Creative
	    Commons Attribution-ShareAlike 2.5 license</a>.
	  </p>
	 <p>
	   <a href="http://creativecommons.org/licenses/by-sa/2.5/">
	      <img src="http://creativecommons.org/images/public/somerights20.gif"
	      	   alt='Attribution-ShareAlike' width='88' height='31'/>
	   </a>
	 </p>
	   <div class='logos'>
	    <a href="http://sourceforge.net/projects/zero-install">
	      <img width="88" height="31" alt="SF logo"
     	       src="http://sourceforge.net/sflogo.php?group_id=7023&amp;type=1"/>
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
	   </div>
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
        <xsl:when test='text()'><xsl:apply-templates select='@*|node()'/></xsl:when>
	<xsl:otherwise>Yes</xsl:otherwise>
      </xsl:choose>
    </td>
  </xsl:template>

  <xsl:template match='*[name() = "toc"]'>
    <xsl:variable name='level'><xsl:value-of select='@level'/></xsl:variable>
    <ol>
    <xsl:for-each select='following::*[name() = $level]'>
      <li><a href="#{generate-id()}"><xsl:value-of select='.'/></a></li>
    </xsl:for-each>
    </ol>
  </xsl:template>

  <xsl:template match='*[name() = "h3" or name() = "h2"]'>
    <xsl:copy>
      <xsl:attribute name='id'><xsl:value-of select="generate-id()"/></xsl:attribute>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
