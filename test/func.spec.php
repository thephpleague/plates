<?php

xdescribe('FuncArgs', function() {
    it('can store func arguments');
    it('can update the name');
    it('can update the args');
});
xdescribe('layoutFunc', function() {
    it('forks a template and sets the layout');
});
xdescribe('sectionFunc', function() {
    it('gets a section from the template sections');
});
xdescribe('startFunc', function() {
    it('starts output buffering and stores a section def');
});
xdescribe('endFunc', function() {
    it('throws an exception if no section_defs have beend defined');
    it('throws an exception if the output buffering level does not match the section def');
    it('cleans the buffer and replaces the section');
    it('cleans the buffer and appends the section');
    it('cleans the buffer and prepends the section');
});
xdescribe('insertFunc', function() {
    it('forks a template and echos the rendered contents');
});
xdescribe('escapeFunc', function() {
    it('it escapes the content with htmlspecialchars');
});
xdescribe('assertArgsFunc', function() {
    it('throws an exception if the num of required args do not exist');
    it('passes through if there are enough args');
    it('appends null to the args to make up the amount of required and defaulted args');
});
xdescribe('aliasNameFunc', function() {
    it('passes through if no aliases are matched');
    it('recursively aliases func names');
});
xdescribe('splitByNameFunc', function() {
    it('allows stacks to be invoked by func name');
    it('passes through if no stack was registered per func name');
});
xdescribe('platesFunc', function() {
    it('creates the default plates func stack');
});
