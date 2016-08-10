# Usage
    git clone git@github.com:godvsdeity/hero-game.git
    cd hero-game/ && composer install
    php app.php

# Util info
You can define battles and characters using `data/config.yml`

# TODO list
- replace `echo`s with output object
- add better exception handling
- add unit tests
- add `SkillEfect` and `Stat` classes:
    - a stat can stack skill effects, and collect the stat value by applying the effects in stack over its default value
    - a skill will be able to cast one or multiple skill effects
    - skil effects will encapsulate the deltas modifiers and will be of two types:
        1. permanent - will directly alter the stat value
        2. turn based - will stack on stat and will alter its value for as long it is stacked; the effect will be removed from stat's stack once its duration limit is reached
