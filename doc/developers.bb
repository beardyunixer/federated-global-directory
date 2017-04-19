[b]Developer Guidelines[/b]

We're pretty relaxed when it comes to developers, mostly, because we haven't got any, but we might get some when this becomes an actual product and we open source the engine, so we still need some guidelines.

[b]Code Style[/b]

All comments should be in English. If you really don't want to use English, please use Hex or Binary instead.

Comments should strive to be as hilarious as possible. Your job isn't to make the code easy to read, it is to give another developer something worth reading. Bonus points if your comments consist primarily of jokes, limericks, and short stories.

Exception: If code actually needs documenting because it doesn't document itself, do it in a doxygen format so we can break that stuff out if we need to.

Use tabs with a width of 3 or 6 for indentation. This is because some people complain about tab widths of 4, and others complain of tab widths of 8. I have yet to hear anyone complain about tab widths of 3 or 6.  For hysterical reasons, most of the code still uses a tab width of 4.  Feel free to change this to 3 or 6.

String concatenation and operators should be separated by whitespace $foo = $bar . $baz instead of $foo=$bar.$baz

Where practical, use single quotes for string variables and double quotes for SQL statements. It's okay to use double quotes for strings if you'll end up having to escape a bunch of characters.

Use whitespace liberally to enhance readability, but keep line length to 80 characters. Bonus points for arranging your code as ASCII art. You win one internet if you arrange your code as ASCII art that represents the funtion it describes.

When creating arrays with many elements, set one key/value pair per line, unless you know the next person in there will be somebody you don't like, and you want to annoy them.

Squiggly brackets go on the same line as the thing which opens the squiggly bracket. They are the last character on the line. Closing squiggly brackets are on a line by themselves. There is nothing funny about squiggly brackets, and under no circumstances should you refer to them as braces.
