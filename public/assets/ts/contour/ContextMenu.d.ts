/**
 * Created by Jason on 4/27/2016.
 */
interface JQuery {
    /**
     * Initializes a context menu
     */
    contextmenu() : JQuery;
    /**
     * Initializes a context menu
     * @param {ContextMenuOptions} options
     */
    contextmenu(options : ContextMenuOptions) : JQuery
}
/**
 * Context menu options
 */
interface ContextMenuOptions {
    /**
     * @var jquery selector of the context menu
     */
    target? : string;
    before? : (e : JQueryEventObject, context: JQuery) => void;
    onItem? : (context : JQuery, e : JQueryEventObject) => void;
    scopes? : string;
}

/**
 *
 *
 All events are fired at the context's menu. Check out dropdown plugin for a complete description of events.

 show.bs.context - This event fires immediately when the menu is opened.
 shown.bs.context - This event is fired when the dropdown has been made visible to the user.
 hide.bs.context - This event is fired immediately when the hide instance method has been called.
 hidden.bs.context - This event is fired when the dropdown has finished being hidden from the user (will wait for CSS transitions, to complete).
 Sample



 Sample Context menu HTML

 <div id="contextMenu1">
     <ul class="dropdown-menu" role="menu">
         <li>
            <a class="context_menu_item" tabindex="-1" href="http://localhost/excel/edit/1">open as Excel</a>
         </li>
         <li>
            <a class="context_menu_item" tabindex="-1" href="javascript: editor.rename(1)">rename</a>
         </li>
         <li>
            <a class="context_menu_item" tabindex="-1" href="javascript: editor.delete(1)">delete</a>
         </li>
     </ul>
 </div>


 USAGE

 $(".tag").each((index, element) => {
            $(element).contextmenu({
                target : "#tagContextMenu",
                before: (e, context) => {
                    return !e.ctrlKey; // do not show if ctr key is hit

                },
                onItem: () => {

                }
            });
        });

 

 using with dynamically added elements

 $('#mt').contextmenu({
        target: '#context-menu',
        scopes: 'tbody > tr',
        onItem: function (row, e) {
            var name = $(row.children('*')[1]).text();
            var action = $(e.target).text();
            alert('You right clicked on ' + name + '\'s row and selected menu item "' + action  + '".');
        }
 *
 */