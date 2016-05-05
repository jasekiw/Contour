Contour language spec

tags

columns are like arrays but they are the wrapper
rows are the the keys in the array.

table-cell tag is an array with only numbers as keys. no tags are envolved


you can build complex arrays by having multiples table-cells 

|   | first_name | last_name |
|---|------------|-----------|
| 0 | jason      | gallavin  |

    #(first_name[0]) + " " + #(last_name[0]) = jason gallavin



columns
			first_name	last_name	age
corporate	jason		gallavin	21
corporate	wayne		gallavin	58

    #(first_name, corporate) + " " + #(last_name, corporate) = "jason gallavin"

alternatively
$(first_name:has(corporate):separate(" "))
if multiple exist a sum function will be called. strings concatenate, numbers add

    # (age:has(corporate)) = 79

to use responses as tags, get the result and wrap it

| myTag        | anotherTag  |
|--------------|-------------|
| "anotherTag" | "some text" |
	
    #(#(myTag)) = "some text"


functions

| myTag                                                                      | anotherTag  | someOtherTag      |
|----------------------------------------------------------------------------|-------------|-------------------|
| if($1 == true)      return #(anotherTag)  else      return #(someOtherTag) | "some text" | "some other text" |

	
	call it by
	
	#(myTag:arg(true)) 	= "some text"
	#(myTag:arg(false)) = "some other text"
	
	
	using multiple tags
	#(myTag,myOtherTag:arg(true))
