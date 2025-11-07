+++
title = "New post"
author = ["David Álvarez Rosa"]
draft = false
+++

Another post and more

This post is for ****testing Hugo themes**** with ox-hugo. It demonstrates many Org/ox-hugo features.


## This is a Level 2 Heading {#this-is-a-level-2-heading}

Here is some ****bold text****, __italic text__, `=highlighted text=`, and `code` inline.


## Unordered List {#unordered-list}

-   Apples
-   Bananas
    -   Ripe
    -   Unripe
-   Oranges


## Ordered List {#ordered-list}

1.  Install Emacs
2.  Install ox-hugo
3.  Write posts in Org-mode


## Definition List {#definition-list}

Term 1 :: Definition of term 1.
Term 2 :: Another definition, with
           multiple lines.


## Source Code (C++) {#source-code--c-plus-plus}

_\* C++ Example \*_

```cpp
#include <iostream>

int main() {
    std::cout << "Hello, ox-hugo test!" << std::endl;
    return 0;
}
```


## Block Quotes {#block-quotes}

> This is a block quote. Great for citing authors or referencing external thoughts.


## Table {#table}

| Name  | Role      | Score |
|-------|-----------|-------|
| Jane  | Reviewer  | 10    |
| John  | Developer | 8     |
| Alice | Designer  | 9     |


## Hyperlinks {#hyperlinks}

Visit the [ox-hugo documentation](https://ox-hugo.scripter.co/) for details.


## Images {#images}

[Hugo Logo](./images/hugo-logo.png)
[External Hugo SVG](https://gohugo.io/images/hugo-logo-wide.svg)


## <span class="org-todo todo TODO">TODO</span> List (Task List) {#list--task-list}

-   [ ] Try this post in your Hugo theme
-   [X] Install ox-hugo
-   [ ] Refine your CSS


## Table of Contents {#table-of-contents}

<div class="ox-hugo-toc toc">

<div class="heading">Table of Contents</div>

- [This is a Level 2 Heading](#this-is-a-level-2-heading)
- [Unordered List](#unordered-list)
- [Ordered List](#ordered-list)
- [Definition List](#definition-list)
- [Source Code (C++)](#source-code--c-plus-plus)
- [Block Quotes](#block-quotes)
- [Table](#table)
- [Hyperlinks](#hyperlinks)
- [Images](#images)
- [<span class="org-todo todo TODO">TODO</span> List (Task List)](#list--task-list)
- [Table of Contents](#table-of-contents)
- [Math](#math)
- [Horizontal Rule](#horizontal-rule)
- [Custom Hugo Shortcodes](#custom-hugo-shortcodes)
- [Emoji](#emoji)
- [Syntax Highlighting](#syntax-highlighting)

</div>
<!--endtoc-->


## Math {#math}

Here is an inline formula: \\( E = mc^2 \\)

Display math:

\\[
\int\_0^\infty e^{-x^2} dx = \frac{\sqrt{\pi}}{2}
\\]


## Horizontal Rule {#horizontal-rule}

---


## Custom Hugo Shortcodes {#custom-hugo-shortcodes}

Callout block:

{&lbrace;% alert note %&rbrace;}
This is a callout using Hugo's built-in shortcodes.
{&lbrace;% /alert %&rbrace;}


## Emoji {#emoji}

:smile: :rocket: :tada:


## Syntax Highlighting {#syntax-highlighting}

See the C++ source code block above.

**End of the post!** 🎉

[^fn:1]: This is a sample footnote. Isn't that neat?
