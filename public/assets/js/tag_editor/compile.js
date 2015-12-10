var UglifyJS = require("C:\\Users\\Jason Gallavin\\AppData\\Roaming\\npm\\uglifyjs");
var result = UglifyJS.minify([ "tagEditor.js" ], {
    outSourceMap: "out.js.map"
});
console.log(result.code); // minified output
console.log(result.map);