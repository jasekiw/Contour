## Contour

## Synopsis

A framework built on laravel used to model data easily and efficiently. When complete this can be a cms that will a web application that could be of 
any type.

## Code Example

DataTags Are used to tag data for later reference.
DataBlocks hold data and can be referenced by DataTags.

Get a tag by it's ID if it is known
$tag = DataTags::get_by_id($id); 

get all root tags in the database

$tags = DataTags::get_by_parent_id(-1);

## Motivation

So many CMS applications are orientated towards one specific type of website. I wanted to make a CMS/Framework that allowed easy extension of functionality to any type of website. 
I came up with Contour to solve this problem.

## Installation

Right now, You download the project, set the database credentials in app/config/database.php and then in terminal run php artisan migrate.

## API Reference

Coming Soon

## This project allows the use of intellisense across the project


##Running

Requires redis.

Windows:
install chocolately
choco install redis-64
run redis-server




