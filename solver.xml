<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>The Solver</h2>

<p>
When you run a program with 0install, 0install must select a version of the program and a compatible version of each dependency it requires, recursively. Choosing the best set of versions from the billions of potential combinations is the job of the <a href='https://github.com/0install/0install/blob/master/ocaml/zeroinstall/solver.mli'>Solver</a> module.
The 0install solver is an adaptation of the <a href='http://www.cjtucker.com/opium.pdf'>OPIUM</a> algorithm (Optimal Package Install/Uninstall Manager).
</p>

<toc level='h3'/>

<h3>Background</h3>

<p>
OPIUM was designed as a replacement solver for Debian's apt-get.
With apt-get, if two programs depend on different versions of the same library, they can't both be installed at once. When you try to install one, apt-get will first uninstall the other. Sometimes this is unavoidable, but in other cases there is a choice of dependency. The choices apt-get makes determine which other packages must be uninstalled, but it doesn't always find the best solution (or even any solution at all). OPIUM solved this problem by ensuring that the best available choice was always made.
</p><p>
0install has a slightly different problem. In 0install, every package is unpacked to its own directory. Libraries are <a href='sharing.html'>shared</a> when possible (two programs depend on the same version), and installed in parallel otherwise. Therefore, installing one program with 0install never requires uninstalling another. However, it is still possible for the dependencies within a single program to conflict with each other. For example, a Python program may depend on "python2.5 or python2.6", but a library it uses may only work with one of them. When running that particular program, 0install must choose a version of Python and a version of the library that are compatible.
</p><p>
While apt-get has to look for conflicts across a very large number of packages (every package installed or being considered for installation), 0install only has to consider the packages needed for the program being run, but it must look at a large number of different possible versions for each package.
</p><p>
Before 2010, 0install used a simple non-backtracking solver, which was possible because most packages had only a few dependencies. Because exactly the same set of versions was available to everyone, if a program was installable by its author then it would be installable everywhere, and even if some dependencies did conflict it was easy enough to list them in some order so that 0install always got a solution. But a couple of things made life more complicated...
</p>

<dl>
<dt>Multi-arch support</dt>

<dd>
A typical modern 64-bit system is also capable of running 32-bit code. However a single program must be either entirely 64-bit or entirely 32-bit. We can't, for example, select a 64-bit version of libgtk and a 32-bit version of Python for a single application. In other words, every 64-bit binary conflicts with every 32-bit binary, which means we have to deal with a lot more conflicts these days.
</dd>

<dt>Native packages</dt>

<dd>
The Sugar developers wanted their 0install packages to depend on existing distribution packages in many cases. The normal way to do this is to provide a 0install download of the dependency (e.g Python 2.6) yourself, but tell 0install it can use a distribution package instead if available. However, the Sugar developers (quite reasonably) wanted to avoid packaging Python at all and depend only on the distribution package. Because Python isn't binary-compatible across versions, they publish separate builds of their software for each version of Python and relied on 0install to choose the one that will work with their users' distributions.
</dd>

</dl>

<p>
These kinds of problems can't be solved using older versions of 0install. The solver would choose the Python 2.6 version of the main program (for example) and then try to select a version of Python 2.6 to go with it. If the distribution only had Python 2.5, this would fail. Aleksey Lim from the Sugar project worked around this problem by <a href='http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/2966'>adding backtracking to the solver</a>. However, this becomes very slow when there are many possible combinations of versions to consider.
</p><p>
<h3>Adapting the OPIUM algorithm</h3>
</p><p>
Initially, I tried following the OPIUM paper closely. There, they represent each possible version of each package as a variable which is 1 if the package is to be installed and 0 if not. They make a list of constraints (expressions that must be true). For example, if we want to select a version of Firefox (either 3.5 or 3.6, but not both at once) we would write:
</p><p>
  firefox3.5 + firefox3.6 = 1
</p><p>
If Firefox 3.6 depends on GTK >= 2.18 then we can express that dependency as:
</p><p>
  gtk2.18.0 + gtk2.18.1 + gtk2.18.2 - firefox3.6 >= 0
</p><p>
This expression can be satisfied by either not choosing Firefox 3.6 (firefox3.6 = 0) or by choosing a compatible version of GTK to go with it.
</p><p>
After writing out all these expressions, we pass them all to a pseudo-boolean constraint solver (I <a href='http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/3054'>tested with minisat+</a>). We also give it a cost function to minimise (e.g. selecting older versions "costs" more, so it chooses newer versions where possible). The solver then tells us which variables should be 1, and these are the versions to use.
</p><p>
This scheme worked, but it turned out to be quite slow. The problem was the cost function: most combinations have a similar cost, which makes it difficult for the solver to narrow the search down quickly. There could even be several solutions with the same cost, so you might get a different set of versions every time you ran it! 
</p><p>
The solution was to optimise one component at a time:
</p>
<ol>
<li>Solve, optimising for the "best" version of Firefox <i>that could be part of a valid selection</i>.
</li><li>Then solve again, for the "best" version of GTK that can be part of a valid combination with the previously selected version of Firefox.
</li><li>... and so on recursively until we have selected a version of every component we need.
</li></ol>
<p>
In fact, we don't need to use a cost function at all. We can just ask whether there is any valid combination involving the best version of Firefox. If not, we ask again for the second best version, etc. The problem can then be simplified to plain old <a href='http://en.wikipedia.org/wiki/Boolean_satisfiability_problem'>Boolean satisfiability</a> and implemented efficiently. A DPLL-based algorithm with conflict-driven learning turned out to be very fast, even <a href='http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/3082'>implemented in pure Python</a>.
</p>

<h3>Worked example</h3>

<p>
  Consider the following case (we're trying to run "prog", of which there are two versions available):
</p>

<ul>
  <li>prog-1 requires lib-1 or lib-2</li>
  <li>prog-2 requires lib-2</li>
  <li>lib-1 requires python-2</li>
  <li>lib-2 requires python-3</li>
  <li>python-3 isn't available</li>
</ul>

<p>
  We first encode these rules into a set of binary expressions to be satisfied:
</p>

<table class='table'>
  <tr><th>Requirement</th>			<th>Boolean expression</th></tr>
  <tr><td>prog-1 requires lib-1 or lib-2</td>	<td>lib-1 or lib-2 or !prog-1</td></tr>
  <tr><td>prog-2 requires lib-2</td>		<td>lib-2 or !prog-2</td></tr>
  <tr><td>lib-1 requires python-2</td>	<td>python-2 or !lib-1</td></tr>
  <tr><td>lib-2 requires python-3</td>	<td>python-3 or !lib-2</td></tr>
  <tr><td>python-3 isn't available</td>		<td>!python-3</td></tr>
</table>

<p>
  In addition, there are some constraints we always have: we must select a version of the program, and we can't select two different versions of any interface:
</p>

<table class='table'>
  <tr><th>Requirement</th>			<th>Boolean expression</th></tr>
  <tr><td>must select some prog</td>	<td>prog-1 or prog-2</td></tr>
  <tr><td>only one prog</td>		<td>at_most_one(prog-1, prog-2)</td></tr>
  <tr><td>only one lib</td>		<td>at_most_one(lib-1, lib-2)</td></tr>
  <tr><td>only one python</td>		<td>at_most_one(python-2, python-3)</td></tr>
</table>

<p>
  Note: we could express at_most_one(a, b) as "!a or !b", but that scales badly when there are lots of versions, so our SAT solver adds at_most_one as a primitive.
</p>

<p>
  Now the solve proceeds as follows:
</p>

<ol>
  <li>Find "unit" clauses. Here we find "!python3".</li>
  <li>Simplify all rules containing python3 to get:
    <ul>
      <li>lib-1 or lib-2 or !prog-1</li>
      <li>lib-2 or !prog-2</li>
      <li>python-2 or !lib-1</li>
      <li>!lib-2</li>
      <li>!python-3</li>
      <li>prog-1 or prog-2</li>
      <li>at_most_one(prog-1, prog-2)</li>
      <li>at_most_one(lib-1, lib-2)</li>
    </ul>
  </li>
  <li>We now have a new unit clause: "!lib-2". Simplify again:
    <ul>
      <li>lib-1 or !prog-1</li>
      <li>!prog-2</li>
      <li>python-2 or !lib-1</li>
      <li>!lib-2</li>
      <li>!python-3</li>
      <li>prog-1 or prog-2</li>
      <li>at_most_one(prog-1, prog-2)</li>
    </ul>
  </li>
  <li>Now we have "!prog-2":
    <ul>
      <li>lib-1 or !prog-1</li>
      <li>!prog-2</li>
      <li>python-2 or !lib-1</li>
      <li>!lib-2</li>
      <li>!python-3</li>
      <li>prog-1</li>
    </ul>
  </li>
  <li>Now we have "prog-1", which gets us "lib-1", which leaves us with the solution:
    <ul>
      <li>lib-1</li>
      <li>!prog-2</li>
      <li>python-2</li>
      <li>!lib-2</li>
      <li>!python-3</li>
      <li>prog-1</li>
    </ul>
  </li>
  <li>0install will then run prog-1 with lib-1 and python-2.</li>
</ol>

<p>
  In the above case, we were able to solve the problem simply by simplifying. Sometimes, we reach a point where there are no unit-clauses left. In that case, we set one undecided variable to try the most optimal remaining combination. If that leads to a solution, we have the optimal solution. If it leads to a conflict, we use "conflict driven learning" to learn a general rule which would avoid us trying that path, backtrack, and resume. The learning system helps to avoid exploring similar wrong-paths as the solve continues.
</p>

<p>
  The key to making this work is picking the most optimal next variable. Each time we need to choose, we do a depth first search of the current dependency tree: if we haven't yet picked a version of prog then we try setting the best remaining one to true (e.g. "prog-2 = true"). If we've already picked a version of prog, we look at its first dependency (always lib in this case) and try the best version of that, and so on.
</p>

<h3>The OCaml implementation</h3>

<p>The blog post <a href='http://roscidus.com/blog/blog/2014/09/17/simplifying-the-solver-with-functors/'>Simplifying the Solver With Functors</a> contains more information about the new OCaml implementation of the solver.</p>

</html>
