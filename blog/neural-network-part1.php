<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite entry PHP file.
 * Copyright (C) 2019-2020 David \'Alvarez Rosa
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 **/
?>


<!DOCTYPE html>

<html lang="en">
   <head>
      <title>Neural Networks | David Álvarez Rosa | Personal Blog</title>
      <meta charset="UTF-8" />
      <meta name="description" content="Implementing a Neural Network from scratch
                  in C++ - Part 1 - The theory | David
                  Álvarez Rosa | Personal Blog" />
      <meta name="keywords" content="Neural Network, C++, Scratch, Fully
                  Connected, Artificial Intelligence, Deep
                  Learning, Implementing, David Álvarez Rosa,
                  David Álvarez, David, Personal Blog, Blog,
                  Entry, Mathematics, Engineering, Technology" />
      <meta name="author" content="David Álvarez Rosa" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="canonical"
            href="https://blog.alvarezrosa.com/neural-network-part1.php" />
      <link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">
      <link rel="manifest" href="site.webmanifest">
      <link rel="mask-icon" href="img/icons/safari-pinned-tab.svg" color="#816363">
      <link rel="shortcut icon" href="img/icons/favicon.ico">
      <meta name="msapplication-TileColor" content="#DBDCDC">
      <meta name="msapplication-config" content="img/icons/browserconfig.xml">
      <meta name="theme-color" content="#FFFFFF">
      <link rel="stylesheet" href="css/main.css" />
      <link rel="stylesheet" href="css/blog.css" />
      <link rel="stylesheet" href="css/animations.css" />
      <link rel="stylesheet" href="fontawesome/css/fontawesome.css" />
      <link rel="stylesheet" href="fontawesome/css/solid.css" />
      <link rel="stylesheet" href="fontawesome/css/brands.css" />
      <link rel="stylesheet" href="highlight/styles/atom-one-dark.css" />
      <script type="application/ld+json">
	     {
		       "@context":"http://schema.org",
		       "@type": "BlogPosting",
		       "image": "img/icons/icon.png",
		       "url": "https://blog.alvarezrosa.com/neural-networks-part1.php",
		       "headline": "Neural Networks - Part 1",
		       "alternativeHeadline": "Neural Networks",
		       "dateCreated": "2019-02-11T11:11:11",
		       "datePublished": "2019-02-11T11:11:11",
		       "dateModified": "2019-02-11T11:11:11",
		       "inLanguage": "en-US",
		       "isFamilyFriendly": "true",
		       "copyrightYear": "2020",
		       "copyrightHolder": "",
		       "author": {
			         "@type": "Person",
			         "name": "David Álvarez Rosa",
			         "url": "https://david.alvarezrosa.com"
		       },
		       "creator": {
			         "@type": "Person",
			         "name": "David Álvarez Rosa",
			         "url": "https://david.alvarezrosa.com/"
		       },
		       "genre":["SEO","JSON-LD"],
		       "articleSection": "Uncategorized posts",
		       "articleBody": "Paste the body of your content in here in plaintext"
	     }
      </script>
   </head>


   <?php include "resources/comment.html" ?>


   <body class="preload">
      <?php
      $currentSite = 'blog';
      $sections = array('Introduction', 'Topology', 'Forward', 'Optimization',
                        'Backward');
      $icons = array('fas fa-percentage', 'fas fa-project-diagram',
                     'fas fa-forward', 'fas fa-chart-line', 'fas fa-backward');
      include 'resources/bodyPreMain.php';
      ?>


      <!-- Main. -->
      <main>
         <p class="fadeIn" style="margin-top: 2.5em;">
            This website does not (and won't ever) use cookies. I value your
            privacy.
         </p>

         <h1 class="fadeIn">
            Implementing a Neural Network from scratch &ndash; Part 1
         </h1>
         <div class="subTitle fadeIn">
            <div class="rightLeftFlex">
               <h4>
                  <i class="fas fa-clock"></i> &nbsp;
                  10 to 15 minutes to read
               </h4>
               <h4>
                  <i class="fas fa-user-edit"></i> &nbsp;
                  <a href="https://david.alvarezrosa.com/"
                     title="David Álvarez Rosa's personal website.">
                     David Álvarez Rosa
                  </a>
               </h4>
            </div>
            <div class="rightLeftFlex">
               <h4>
                  <i class="fas fa-tags"></i> &nbsp;
                  Neural Network - AI - Deep Learning
                  <span id="callOutTags1" class="callOut">
                     <a href="javascript:enlargeCallOut('callOutTags1');"
                        title="Click to see this information bigger.">
                        <i class="moreTags fas fa-plus"></i>
                     </a>
                     <span>
                        <p>Machine Learning, C++, Implementation, Scratch.</p>
                     </span>
                  </span>
               </h4>
               <h4>
                  <i class="fas fa-calendar-alt"></i> &nbsp;  March 9, 2020
               </h4>
            </div>
            <p class="marginTopAux">
               <strong class="abstract">Abstract</strong>. The first entry of this
               blog series of implementing a Neural Network in C++ will be covering
               the <strong>mathematical theory</strong> behind the fully connected
               layered artificial neural networks. We will start by defining its
               <a href="#sec:topology" title="Scroll to network topology section.">
                  topology</a> and its core <strong>components</strong>. Then we will
               dicuss how a neural network works (namely
               <a href="#sec:forward" title="Scroll to feed forward section.">
                  forward propagation</a>) This blog entry will finish by
               <strong>reformulating</strong> the learning problem from a
               mathematical optimization point of view and deriving
               the <em>well-known</em>
               <a href="#sec:backward" title="Scroll to backward propagation section">
                  backward propagation</a> formula.
            </p>
         </div>

         <div class="anchor" id="sec:introduction">
            <i class="bouncingHand fas fa-hand-point-right"></i>
            <a onclick="javascript:takeMeBack();"
               class="undoAnchor"
               title="Take me back where I was.">
               <i class="fas fa-fast-backward"></i>
            </a>
         </div>
         <section class="hidden">
            <h2> Introduction
               <a href="#sec:intro"
                  title="Go to introduction section.">
                  <i class="linkIcon fas fa-link"></i>
               </a>
               <i class="rightIcon fas fa-percentage"></i>
            </h2>
            <p>
               A <strong>neural network</strong>, more properly referred to as
               an <em>artificial</em> neural network (ANN) are computing systems
               vaguely inspired by the biological neural networks that constitute
               animal brains. Dr.
               <a href="https://en.wikipedia.org/wiki/Robert_Hecht-Nielsen"
                  rel="nofollow noopener"
                  target="_blank"
                  title="Robert Hecht-Nielsen - Wikipedia">
                  Robert Hecht-Nielsen <i class="fas fa-external-link-alt"></i>
               </a>
               (inventor of one of the first neurocomputers) defines a neural network
               as:
            </p>
            <blockquote>
               <p>
                  <i class="fas fa-pencil-alt fa-lg"></i>
                  <em> ...a computing system made up of a number of simple, highly
                     interconnected processing elements, which process information by
                     their dynamic state response to external inputs.</em>
               </p>
               <p class="quoteAuthor">
                  <a href="https://en.wikipedia.org/wiki/Robert_Hecht-Nielsen"
                     rel="nofollow noopener"
                     target="_blank"
                     title="Robert Hecht-Nielsen - Wikipedia">
                     ~Robert Hecht-Nielsen <i class="fas fa-external-link-alt"></i>
                  </a>
               </p>
               <div class="clear"></div>
            </blockquote>
            <p>
               An ANN is based on a collection of connected units, that are called
               <em>artificial</em> <strong>neurons</strong>, which loosely model the
               neurons in a biological brain. Each connection, like the
               <a href="https://en.wikipedia.org/wiki/Synapse"
                  rel="nofollow noopener"
                  target="_blank"
                  title="Synapse - Wikipedia">
                  synapses <i class="fas fa-external-link-alt"></i>
               </a>
               in a biological brain, can transmit a signal to other neurons. An
               artificial neuron that receives a signal then processes it and can
               signal neurons connected to it.
            </p>
            <div class="anchor" id="subsec:math-perspective">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <h3>
               Mathematical perspective
               <a href="#subsec:math-perspective"
                  title="Go to mathematical perspective subsection.">
                  <i class="linkIcon fas fa-hashtag"></i>
               </a>
            </h3>
            <p>
               Altough the analogy made above of an ANN with a biological brain,
               there is no need for this, we can just think of a neural network as a
               mathematical <strong>optimization</strong> problem. We can think of
               the whole network to be a function that takes some inputs to some
               outputs, and this function dependent on <strong>parameters</strong>.
               The idea is to adjust this parameters to get a function that works
               well with some known dataset, and we will trust that it will
               generalize well. If the network is big enough and we carefully adjust
               the parameters, we will be able to learn and calculate very complex
               functions.
            </p>
         </section>

         <div class="anchor" id="sec:topology">
            <i class="bouncingHand fas fa-hand-point-right"></i>
            <a onclick="javascript:takeMeBack();"
               class="undoAnchor"
               title="Take me back where I was.">
               <i class="fas fa-fast-backward"></i>
            </a>
         </div>
         <section class="hidden">
            <h2> Network topology
               <a href="#sec:topology"
                  title="Go to network topology section.">
                  <i class="linkIcon fas fa-link"></i>
               </a>
               <i class="rightIcon fas fa-project-diagram"></i>
            </h2>
            <div class="anchor" id="p:intro-toplogy">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <p>
               More concretly, in this blog series we will be discussing
               the <strong>fully-connected</strong> layered ANN, this is a case in
               which neurons are organized in <strong>layers</strong> and neurons
               between adjacent layers are required to be connected (and
               weighted). In the
               <a href="#fig:neural-network">figure 1</a> below, is shown an example
               of a fully connected neural network with two hidden layers.
            </p>
            <div class="anchor" id="fig:neural-network">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <figure>
               <img src="img/blog/Neural Network.jpeg"
                    alt="Fully connected neural network example with two hidden layers.">
               <figcaption>
                  <strong>Figure 1</strong>: Fully connected ANN with two hidden layers.
               </figcaption>
            </figure>
            <p>
               We now will discuss the main parts that constitute this type of neural
               networks:
               <a href="#subsec:layers" title="Go to layers subsection">layers</a>,
               <a href="#subsec:neurons" title="To to neurons subsection">neurons</a>
               and
               <a href="#subsec:connections" title="To to connections subsection">
                  connections</a>.
            </p>
            <div class="anchor" id="subsec:layers">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <h3>
               Layers
               <a href="#subsec:layers"
                  title="Go to layers subsection.">
                  <i class="linkIcon fas fa-hashtag"></i>
               </a>
            </h3>
            <p>
               The <strong>layers</strong> are just a collection of neurons, we will
               distinguish between three types depending on its position in the
               network.
            </p>
            <ul>
               <li>
                  <strong>Input</strong> layer: patterns are presented to the
                  network via this layer.
               </li>
               <li>
                  <strong>Hidden</strong> layers: all the inner layers.
               </li>
               <li>
                  <strong>Output</strong> layer: is the last layer, where
                  the <em>answer</em> is obtained.
               </li>
            </ul>
            <p>
               We will denote with $L$ the number of layers and with $n_l$ the
               size of the $l$-th layer.
            </p>
            <p>
               To gain some intuition on how this work, let's think about the
               handwritten recognition problem. Suppose we have a set of images with
               handwritten digits in it and that we will like to implement an ANN
               that is capable of recognizing that digits. In this case, if the
               digits images are of 28x28 pixels, the <strong>input layer</strong>
               will consists of 784 (28x28) neurons and each neuron will hold the
               grayscale value of a pixel. For the <strong>output layer</strong> we
               will need 10 neurons (one for each number between 0 and 9), and we
               will like that when we <em>feed</em> our network with an image holding
               a handwritten number 3, then the ouput is one 1 in the position
               corresponding to the number 3 and the rest of zeros.
            </p>
            <div class="anchor" id="subsec:neurons">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <h3>
               Neurons
               <a href="#subsec:neurons"
                  title="Go to optimization problem subsection.">
                  <i class="linkIcon fas fa-hashtag"></i>
               </a>
            </h3>
            <p>
               Neurons are the core component of any neural network. Basically
               there are three subparts that form a neuron.
            </p>
            <ul>
               <li>
                  <strong>Value</strong>: each neuron holds a value, it will be
                  denoted by $x_i^l \in \mathbb{R}$ for the $i$-th neuron in the
                  $l$-th layer. Of course, it should be satisfied $1 \leq i \leq
                  n_l$. We will use use the notation $\mathbf{x}^l$ for
                  the <strong>vector</strong> of all the values in the $l$-th level.
                  When we speak of the input vector, we may ommit the
                  superindex, i.e. we will use $\mathbf{x}$ to denote
                  $\mathbf{x}^0$. Similarly, for the output layer, we will use
                  $\mathbf{\hat{y}}$ to refer to $\mathbf{x}^L$.
               </li>
               <li>
                  <strong>Bias</strong>: also each neuron has a bias, denoted as
                  $b_i^l$ for the $i$-th neuron in the $l$-th layer. Is then
                  true that $1 \leq i \leq n_l$. The vector of all biases in the
                  $l$-th layer will be denoted by $\mathbf{b}^l$.
               </li>
               <li>
                  <strong>Activation function:</strong> all neurons have an activation
                  function $f_i^l \in \mathcal{C}^1(\mathbb{R}, \mathbb{R})$ for the
                  $i$-th neuron in the $l$-th layer. Of course, it is needed $1
                  \leq i \leq n_l$.
                  <span id="callOutFunctions" class="callOut">
                     <a href="javascript:enlargeCallOut('callOutFunctions');"
                        title="Click to see this information bigger.">
                        <i class="fas fa-info-circle"></i>
                     </a>
                     <span>
                        <p>
                           Usually all the activation functions
                           are <strong>neuron-independent</strong> (i.e. $f_i^l$ does not
                           really depend on $i$ or $l$).
                        </p>
                        <p>
                           The <strong>regularity</strong> assummed for this functions is
                           important, since we will be optimizing in the future by taking
                           derivatives.
                        </p>
                     </span>
                  </span>
               </li>
            </ul>
            <div class="anchor" id="subsec:connections">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <h3>
               Connections
               <a href="#subsec:connections"
                  title="Go to connections subsection.">
                  <i class="linkIcon fas fa-hashtag"></i>
               </a>
            </h3>
            <p>
               As we discussed
               <a href="#p:intro-toplogy"
                  title="Scroll to above discussion.">
                  above</a>,
               all neurons between adjacent layers are required to be connected, this
               are the connections, that should have associated
               a <strong>weight</strong>. For the connection between the $i$-th
               neuron in the $l$-th layer and the $j$-th neuron in the $l+1$-th
               layer, we will denote this weight by $w_{ij}^l \in \mathbb{R} $. The
               set of all this weights is as follows, \[ \{w_{ij}^l \mid 1 \leq i
               \leq n_{l}, \, 1 \leq j \leq n_{l+1}, \, 1 \leq l < L \} \subset
               \mathbb{R}. \]
            </p>
            <p>
               The <strong>matrix</strong> of all weights in the $l$-th layer will be
               denoted by $\mathbf{W}^l$. This is, $(\mathbf{W}^l)_{ij} = w_{ij}^l$.
            </p>
         </section>

         <div class="anchor" id="sec:forward">
            <i class="bouncingHand fas fa-hand-point-right"></i>
            <a onclick="javascript:takeMeBack();"
               class="undoAnchor"
               title="Take me back where I was.">
               <i class="fas fa-fast-backward"></i>
            </a>
         </div>
         <section class="hidden">
            <h2> Feed forward
               <a href="#sec:forward"
                  title="Go to feed forward section.">
                  <i class="linkIcon fas fa-link"></i>
               </a>
               <i class="rightIcon fas fa-forward"></i>
            </h2>
            <p>
               From now on let's suppose we are working with a fully-connected neural
               network, with $L$ different layers and we will be using the same
               notation used before.' The values of the neurons can be computed as
               follows:
               \[
               x_i^l = f_i^l \left( \sum_{k=1}^{n_{l-1}} w_{ik}^{l-1} x_{k}^{l-1} +
               b_i^l \right).
               \]
            </p>
            <p>
               This formula is sometimes referred to as the feed-forward formula or
               forward propagation. It's important to note that it's a recursive
               formula, once the values the neurons in the the input layer are known,
               we can iterate computing the values of the neurons in the the next
               adjacent layer, until we reach the output layer. In this network we
               can think of <em>information</em> travelling in one direction,
               forward, from the input layer, through the hidden layers to the output
               neurons.
            </p>
         </section>

         <div class="anchor" id="sec:optimization">
            <i class="bouncingHand fas fa-hand-point-right"></i>
            <a onclick="javascript:takeMeBack();"
               class="undoAnchor"
               title="Take me back where I was.">
               <i class="fas fa-fast-backward"></i>
            </a>
         </div>
         <section class="hidden">
            <h2> Optimization problem
               <a href="#sec:optimize"
                  title="Go to optimization problem section.">
                  <i class="linkIcon fas fa-link"></i>
               </a>
               <i class="rightIcon fas fa-chart-line"></i>
            </h2>
            <p>
               In order to being able to <strong>train</strong> our neural network,
               is mandatory to define an <strong>error function</strong> (also known
               as loss function) that quantifies how <em>good</em> or <em>bad</em>
               the neural network is performing when <em>feeded</em> with a
               particular dataset. We will you the below notation:
            </p>
            <p>
            </p>
            <ul>
               <li>
                  <strong>Dataset</strong>: will be denoted by $\Omega$ and consists
                  in input-output pairs $(\mathbf{x}, \mathbf{y})$, where $\mathbf{x}$
                  represents the input and $\mathbf{y}$ the desired output. We shall
                  denote the size (cardinal) of the <strong>dataset</strong> by
                  $N$. Of course, in terms of our ANN $\mathbf{x}$ corresponds to the
                  values of the first (or input) layer and $\mathbf{y}$ to the output
                  (or last) layer.
               </li>
               <li>
                  <strong>Parameters</strong>: the parameters of our ANN are both the
                  connection weights $w_{ij}^l$ and the biases $b_i^l$, we will denote
                  the set of all parameters by $\theta$. Keep in mind that $\theta$ is
                  just a set of real vectors of $\mathbb{R}^D$ where $D$ denotes the
                  number of weigths and biases.
               </li>
            </ul>
            <p>
               The error function quantifies how different is the desired output
               $\mathbf{y}$ and the calculated (<em>predicted</em>) output
               $\mathbf{\hat{y}}$ of the neural network on input $\mathbf{x}$ for a
               set of input-output pairs $(\mathbf{x}, \mathbf{y}) \in \Omega$ and a
               particular value of the parameters $\mathbf{\theta}$. We will denote
               the error funcion by $E(\Omega, \theta)$ and we will assume that is
               continuosly differentiable (i.e. $\mathcal{C}^1$).
            </p>
            <p>
               It's' common (and we will assume it that way) that the error funcion
               is a mean of the errors of a particular pair $(\mathbf{x}, \mathbf{y})
               \in \Omega$. This is, there exists a continuosly differentiable
               function $e(\mathbf{x}, \mathbf{y}, \theta)$ such that:
               \[
               E(\Omega, \theta) =
               \frac{1}{N} \sum_{(\mathbf{x}, \mathbf{y}) \in \Omega}
               e(\mathbf{x}, \mathbf{y}, \theta).
               \]
            </p>
            <p>
               Now, what we will want to do is to optimize (minimize) this error
               function in $\theta$. This is, given a dataset $\Omega$ we will want
               to approximate
               \[
               \DeclareMathOperator*{\argmin}{arg\,min}
               \hat{\theta} = \argmin_{\theta} E(\Omega, \theta),
               \]
               given that the above exists.
            </p>
            <div class="anchor" id="subsec:errorFuncs">
               <i class="bouncingHand fas fa-hand-point-right"></i>
               <a onclick="javascript:takeMeBack();"
                  class="undoAnchor"
                  title="Take me back where I was.">
                  <i class="fas fa-fast-backward"></i>
               </a>
            </div>
            <h3>
               Error functions
               <a href="#subsec:errorFuncs"
                  title="Go to error functions subsection.">
                  <i class="linkIcon fas fa-hashtag"></i>
               </a>
            </h3>
            <p>
               To get a more practical idea of what the error functions are, we show
               some examples below.
            </p>
            <ul>
               <li>
                  <strong>Euclidian norm</strong>: the error function of a particular
                  element of the dataset is given by the euclidian distance between
                  the <em>predicted</em> value $\mathbf{\hat{y}}$ and the expected
                  output $\mathbf{y}$,
                  \[
                  e(\mathbf{x}, \mathbf{y}, \theta) =
                  ||\mathbf{y} - \mathbf{\hat{y}(\mathbf{x}, \theta)}||_2.
                  \]
                  Usually is considered the <strong>square</strong> of the euclidian
                  norm, since leads to the same optimization problem.
               </li>
               <li>
                  <strong>Cross-entropy</strong>: it is commonly used in
                  classification problems,
                  \[
                  e(\mathbf{x}, \mathbf{y}, \theta) =
                  \sum_{i = 1}^{n_L} y_i \cdot \log{\hat{y}_i(\mathbf{x}, \theta)}.
                  \]
               </li>
            </ul>
         </section>

         <div class="anchor" id="sec:backward">
            <i class="bouncingHand fas fa-hand-point-right"></i>
            <a onclick="javascript:takeMeBack();"
               class="undoAnchor"
               title="Take me back where I was.">
               <i class="fas fa-fast-backward"></i>
            </a>
         </div>
         <section class="hidden">
            <h2> Backward propagation
               <a href="#sec:backward"
                  title="Go to backward propagation section.">
                  <i class="linkIcon fas fa-link"></i>
               </a>
               <i class="rightIcon fas fa-backward"></i>
            </h2>
            <p>
               First ñaslkdjf asdf kasjdf asd.
            </p>
         </section>

         <div class="sourceCode">
            <button onclick="copyToClipboard('prueba');" title="Copy to clipboard.">
               <i class="copy fas fa-clone fa-2x"></i>
               <i class="copyOkey fas fa-check-circle fa-2x"></i>
               <i class="copyError fas fa-exclamation-triangle fa-2x"></i>
            </button>

            <pre><code class="language-c++" id="prueba">#include
#include "Defs.hh"
#include "Math.hh"
#include "NeuralNetwork.hh"
#include "Data.hh"


int main() {
  std::cout.setf(std::ios::fixed);
  std::cout.precision(5);

  // Read data.
  const int sizeTrainDataset = 1;
  std::ifstream fileTrain("data/train.dat");
  for (int i = 0; i < sizeTrainDataset; ++i)
    trainDataset.push_back(Data(fileTrain));

  const int sizeTestDataset = 100;
  std::ifstream fileTest("data/test.dat");
  for (int i = 0; i < sizeTestDataset; ++i)
    testDataset.push_back(Data(fileTest));

  // Choose model and initialize Neural Network.
  NeuralNetwork neuralNetwork(neuronsPerLayer);

  // Train Neural Network.
  neuralNetwork.train(trainDataset, 5);

  // Test Neural Network.
  neuralNetwork.test(testDataset);
}</code></pre>
         </div>

         <div id="blogControllers" class="fadeIn">
            <button class="blogButton blogPrevious"
                    onclick="window.location.href = 'hello-world.php';">
               <i class="fas fa-arrow-left"></i> Previous blog entry
            </button>
            <button class="blogButton blogNext blogButtonInactive">
               Next blog entry <i class="fas fa-arrow-right"></i>
            </button>
         </div>
      </main>


      <!-- License (Creative Commons). -->
      <?php include "resources/license.html"; ?>


    <!-- Footer. -->
    <?php include "resources/footer.php"; ?>


    <!-- Javascript files. -->
    <script src="js/main.js"></script>
    <script src="js/shortcuts.js"></script>
    <script src="js/confetti.js"></script>
    <!-- Include MathJax. -->
    <script id="MathJax-script" data-src="mathjax/tex-svg.js" async></script>
    <!-- Include highlight.js (for syntax highlighting source code). -->
    <script src="highlight/highlight.pack.js"></script>
    <script src="highlight/highlight-line-numbers.min.js"></script>
    <script src="js/blog.js"></script>

    <script type="text/javascript">
      // Warn that this website is under construction.
      setTimeout( function() { showInfo("welcomeUser"); }, 3500);
    </script>
  </body>
</html>
