<?php $this->layout('layout', ['title' => 'User Profile']) ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($name)?>!</p>

<?php $this->insert('sidebar') ?>