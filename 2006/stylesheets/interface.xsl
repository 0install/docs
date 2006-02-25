<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:zi="http://zero-install.sourceforge.net/2004/injector/interface"
		version="1.0">

  <xsl:output method="xml" encoding="utf-8"
	doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

  <xsl:template match="/zi:interface">
    <html>
      <head>
        <title>
          <xsl:value-of select="zi:name"/>
        </title>
	<style type='text/css'>
	  body { margin: 2em; background: white; color: black;}
	  dt { font-weight: bold; text-transform:capitalize; }
	  dd { padding-bottom: 1em; }
	  dl.group { margin: 0.5em; padding: 0.5em; border: 1px dashed #888;}
	  dl.impl { padding: 0.2em 1em 0.2em 1em; margin: 0.5em; border: 1px solid black; background: #ffa;}
	  pre { background: #ddd; color: black; padding: 0.2cm; }
	</style>
      </head>
      <body>
        <h1><xsl:value-of select="zi:name"/> - <xsl:value-of select='zi:summary'/></h1>
	<p>This is a Zero Install feed. To run this program from the command-line, use this
	command:</p>
	<pre>$ 0launch <xsl:value-of select='/zi:interface/@uri'/></pre>
	<p>
	Users of graphical environments can drag <a href='{/zi:interface/@uri}'>the feed's URL</a> to
	an installer such as <a href='http://rox.sourceforge.net/desktop/AddApp'>AddApp</a>.
	</p>
	<p>
	If you don't have the <b>0launch</b> command, download it from
	<a href='http://0install.net/injector.html'>the 0install.net web-site</a>, which also contains
	documentation about how the Zero Install system works.</p>

	<dl>
	  <xsl:apply-templates mode='dl' select='*|@*'/>
	</dl>
	<h2>Available versions</h2>
        <xsl:apply-templates select='zi:group|zi:requires|zi:implementation'/>
      </body>
    </html>
  </xsl:template>
  
  <xsl:template mode='dl' match='/zi:interface/@uri'>
    <dt>Full name</dt><dd><a href='{.}'><xsl:value-of select="."/></a></dd>
  </xsl:template>

  <xsl:template mode='dl' match='zi:description'>
    <dt>Description</dt><dd><xsl:value-of select="."/></dd>
  </xsl:template>

  <xsl:template mode='dl' match='zi:icon'>
    <dt>Icon</dt><dd><img src='{@href}'/></dd>
  </xsl:template>

  <xsl:template mode='dl' match='*|@*'/>

  <xsl:template match='zi:group'>
    <dl class='group'>
      <xsl:apply-templates mode='attribs' select='@stability|@version|@id|@arch|@released'/>
      <xsl:apply-templates select='zi:group|zi:requires|zi:implementation'/>
    </dl>
  </xsl:template>

  <xsl:template match='zi:requires'>
    <dt>Requires</dt>
    <dd><a href='{@interface}'><xsl:value-of select='@interface'/></a></dd>
  </xsl:template>

  <xsl:template match='zi:implementation'>
    <dl class='impl'>
      <xsl:apply-templates mode='attribs' select='@stability|@version|@id|@arch|@released'/>
      <xsl:apply-templates/>
    </dl>
  </xsl:template>

  <xsl:template mode='attribs' match='@*'>
    <dt><xsl:value-of select='name(.)'/></dt>
    <dd><xsl:value-of select='.'/></dd>
  </xsl:template>

  <xsl:template match='zi:archive'>
    <dt>Download</dt>
    <dd><a href='{@href}'><xsl:value-of select='@href'/></a>
    (<xsl:value-of select='@size'/> bytes)</dd>
  </xsl:template>

</xsl:stylesheet>
