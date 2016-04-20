<?php
namespace app\libraries\theme\userInterface;

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/17/2015
 * Time: 10:40 AM
 */
class FileManager
{
        public static function render() {
            ?>
            <div class="file-manager">
                <ul class="list-inline file-main-menu">
                    <li>
                        <a href="#">
                            <span class="fa-stack fa-lg"><i class="fa fa-file-o fa-stack-2x"></i><i class="fa fa-plus-square-o fa-stack-1x"></i></span> New File
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa-stack fa-lg"><i class="fa fa-folder-o fa-stack-2x"></i><i class="fa fa-plus-square-o fa-stack-1x"></i></span> New Folder
                        </a>
                    </li>
                    <li><a href="#" class="inactive"><i class="fa fa-copy"></i> Copy</a></li>
                    <li>
                        <a href="#" class="inactive">
                            <span class="fa-stack fa-lg"><i class="fa fa-file-o fa-stack-2x"></i><i class="fa fa-arrow-right fa-stack-1x"></i></span> Move File
                        </a>
                    </li>
                    <li><a href="#"><i class="fa fa-upload"></i> Upload</a></li>
                    <li><a href="#" class="inactive"><i class="fa fa-download"></i> Download</a></li>
                    <li><a href="#" class="inactive"><i class="fa fa-close"></i> Delete</a></li>
                    <li>
                        <a href="#" class="inactive">
                            <span class="fa-stack fa-lg"><i class="fa fa-exchange fa-stack-1x"></i><i class="fa fa-file-o fa-stack-2x"></i></span> Rename
                        </a>
                    </li>
                    <li><a href="#" class="inactive"><i class="fa fa-edit"></i> Edit</a></li>
                    <li><a href="#" class="inactive"><i class="fa fa-eye"></i> View</a></li>
                    <li><a href="#" class="inactive"><i class="fa fa-file-zip-o"></i> Compress</a></li>
                </ul>
                <div class="row">
                    <div class="col-lg-2 col-md-4">
                        <div class="input-group">
                            <input type="text" value="/my-directory/projects/" class="form-control">
                            <span class="input-group-btn"><button class="btn btn-default" type="button">Go</button></span>
                        </div>
                        <div class="well tree-wrapper">
                            <div id="tree-file-manager" class="jstree jstree-1 jstree-default jstree-loading" role="tree" tabindex="0" aria-activedescendant="j1_loading" aria-busy="true"><ul class="jstree-container-ul jstree-children" role="group"><li id="j1_loading" class="jstree-initial-node jstree-loading jstree-leaf jstree-last" role="tree-item"><i class="jstree-icon jstree-ocl"></i><a class="jstree-anchor" href="#"><i class="jstree-icon jstree-themeicon-hidden"></i>Loading ...</a></li></ul></div>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-8">
                        <ul class="list-inline file-secondary-menu">
                            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                            <li><a href="#"><i class="fa fa-level-up"></i> Up One Level</a></li>
                            <li><a href="#"><i class="fa fa-arrow-left"></i> Back</a></li>
                            <li><a href="#"><i class="fa fa-arrow-right"></i> Forward</a></li>
                            <li><a href="#"><i class="fa fa-refresh"></i> Reload</a></li>
                            <li><a href="#"><i class="fa fa-check-square-o"></i> Select All</a></li>
                            <li><a href="#"><i class="fa fa-square-o"></i>  Unselect All</a></li>
                        </ul>
                        <div class="table-responsive">
                            <div id="datatable-file-manager_wrapper" class="dataTables_wrapper no-footer"><div class="DTTT_container"></div><div class="clear"></div></div><table id="datatable-file-manager" class="table table-sorting table-dark-header datatable dataTable no-footer DTTT_selectable" style="width: 1249px;">
                                <thead>
                                <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="datatable-file-manager" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending" style="width: 181px;">Name</th><th class="sorting" tabindex="0" aria-controls="datatable-file-manager" rowspan="1" colspan="1" aria-label="Size: activate to sort column ascending" style="width: 155px;">Size</th><th class="sorting" tabindex="0" aria-controls="datatable-file-manager" rowspan="1" colspan="1" aria-label="Last Modified: activate to sort column ascending" style="width: 322px;">Last Modified</th><th class="sorting" tabindex="0" aria-controls="datatable-file-manager" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" style="width: 169px;">Type</th><th class="sorting" tabindex="0" aria-controls="datatable-file-manager" rowspan="1" colspan="1" aria-label="Permissions: activate to sort column ascending" style="width: 307px;">Permissions</th></tr>
                                </thead>
                                <tbody><tr role="row" class="odd">
                                    <td class="sorting_1">
                                        <i class="fa fa-file-image-o"></i> BigFile-Image.jpg
                                    </td>
                                    <td>12 MB</td>
                                    <td>14/01/2015</td>
                                    <td>Image</td>
                                    <td>644</td>
                                </tr>
                                <tr role="row" class="even">
                                    <td class="sorting_1">
                                        <i class="fa fa-file-word-o"></i> BigFile-Word.docx
                                    </td>
                                    <td>200 MB</td>
                                    <td>09/01/2015</td>
                                    <td>Word</td>
                                    <td>644</td>
                                </tr><tr role="row" class="odd"><td class="sorting_1"><i class="fa fa-folder"></i> Fast E-Commerce</td><td>0 KB</td><td>18/01/2015</td><td>Directory</td><td>755</td></tr><tr role="row" class="even"><td class="sorting_1"><i class="fa fa-folder"></i> Project 123GO</td><td>53 KB</td><td>17/12/2014</td><td>Directory</td><td>755</td></tr><tr role="row" class="odd"><td class="sorting_1"><i class="fa fa-folder"></i> SEO</td><td>0 KB</td><td>05/01/2015</td><td>Directory</td><td>755</td></tr><tr role="row" class="even"><td class="sorting_1"><i class="fa fa-folder"></i> Spot Media</td><td>0 KB</td><td>22/12/2014</td><td>Directory</td><td>755</td></tr><tr role="row" class="odd"><td class="sorting_1"><i class="fa fa-folder"></i> Web Redesign</td><td>153 KB</td><td>23/02/2015</td><td>Directory</td><td>755</td></tr></tbody>
                            </table>
                        </div>
                        <div id="contextMenuFileManager">
                            <ul class="dropdown-menu context-menu" role="menu">
                                <li><a tabindex="-1" href="#"><i class="fa fa-download"></i> Download</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-eye"></i> View</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-edit"></i> Edit</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-external-link"></i> Move</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-copy"></i> Copy</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-terminal"></i> Rename</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-lock"></i> Change Permission</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-remove"></i> Delete</a></li>
                                <li><a tabindex="-1" href="#"><i class="fa fa-file-zip-o"></i> Compress</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
}