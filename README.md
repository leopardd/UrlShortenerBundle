# UrlShortenerBundle

## Getting started

```
1. Install
$ composer require leopardd/url-shortener-bundle

2. Register bundle
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Leopardd\Bundle\UrlShortenerBundle\LeoparddUrlShortenerBundle(),
    ];
    
    // ...
}
  
3. (optional) Setup parameter

// config.yml
leopardd_url_shortener:
    hashids:
        salt: new salt
        min_length: 10
        alphabet: abcdefghijklmnopqrstuvwxyz1234567890

4. Update database schema
$ php app/console doctrine:schema:update --force
```

## Folder structure

```
Controller
DependencyInjection
Entity..................represent table structure
Event
Exception
Factory.................create Entity instance
Repository..............all interaction with database
Resources
Service.................contain business logic
```

## Feature & Update & Note

- [x] Example project: [leopardd/bitly](https://github.com/leopardd/bitly)
- [ ] Message queue
- [ ] Caching (e.g. Redis)
- Scaling
  - [ ] Separate host/db/table/id
  - [ ] Docker swarm setup
  - [ ] what happened when ID reach maximum of MySQL
  - [ ] Many databases
  - [ ] Random bulk generate URL for test
- [x] Case-sensitive
- [x] Remove trailing before processing
- [ ] Change naming `EncodeService` to `ProcessService`
- [ ] Test `EventDispatcher`
- [ ] Using [Symfony\Component\Validator](https://symfony.com/doc/current/validation.html) as a service + JMS to validate data
- [ ] Implement [FriendsOfSymfony/FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
- Properties
  - [ ] Custom code
  - [ ] Hits
  - [ ] Expiry date
- Validate url
  - [x] Is url
  - [x] Remove trailing slash
  - [x] No more than 255 character
- Research
  - [ ] [Ph3nol/UrlShortenerBundle](https://github.com/Ph3nol/UrlShortenerBundle)
  - [ ] [mremi/UrlShortenerBundle](https://github.com/mremi/UrlShortenerBundle)
  - [x] gomeeki/url-shortener-bundle](https://github.com/gomeeki/url-shortener-bundle/)

## Algorithm

### Get "short-url" process

1. Insert "long-url" into database then return `row-id`
2. Encode `row-id` then save it

### Redirect "short-url" process

1. Decode incoming "short-url" then we get `row-id`
2. Return item in that `row-id`

### Reason behind "row-id" approach

Short url: produce shortened version of url
1. Generate: produce a shortened version of the URL submitted
2. Lookup: when the shortened version is called, look up this reference in database then return it

And the challenge is
1. Lookup time
2. Allow very very large number of unique ids and at the same time
3. Keep the ID length as small as possible
4. ID should be sort of user friendly and possibly a memorable (if possible)
5. Scale with multiple instances (Sharding)
6. What happens when ID reach the maximum value e.g. (if the length is 7 containing [A-Z, a-z, 0-9], we can serve 62^7 (~35k billion) urls)
7. Replication, database can be crashed by many problems, how to replicate instances, recover fast ?, keep read / write consistent ?

In this bundle, we will focus on point 1.
How we can reduce loop-up time.

```
Table stucture
id: number
code: shortened version of long url
url: long url

Attempt 1
- Generate: random id
- Lookup: simple loop up (O(n))

Attempt 2
- Generate: hash function from long url
- Lookup: simple loop up (O(n))

Attempt 3
- Generate: hash function from long url
- Lookup: bloom filters

Attempt 4
- Generate: hash function from record id
- Lookup: decoding (O(1))
```

So, In this project decide to using "Attemp 4" by using [Hashids](http://hashids.org/php/) for hash function (Hashids will generate unique code from difference id)

## Reference && Tool

- Inspired by [gomeeki/url-shortener-bundle](https://github.com/gomeeki/url-shortener-bundle/)
- [Hashids](http://hashids.org/php/)
- [RegExr](http://regexr.com/)

### Algorithm

- [How to code a URL shortener?](http://stackoverflow.com/questions/742013/how-to-code-a-url-shortener)
- [What are the http://bit.ly and t.co shortening algorithms?](https://www.quora.com/What-are-the-http-bit-ly-and-t-co-shortening-algorithms)
- [Create a TinyURL System](http://blog.gainlo.co/index.php/2016/03/08/system-design-interview-question-create-tinyurl-system/)
- Bloom filter
  - [What are Bloom filters?](https://blog.medium.com/what-are-bloom-filters-1ec2a50c68ff)
  - [Practical uses of bloom filter – SPAM filter and URL shortener and Weak Password Dict](https://theworldsoldestintern.wordpress.com/2012/11/03/practical-uses-of-bloom-filter-spam-filter-and-url-shortener/)
  - [Bloom Filters by Example](https://llimllib.github.io/bloomfilter-tutorial/)
  - [What is the advantage to using bloom filters?](http://stackoverflow.com/questions/4282375/what-is-the-advantage-to-using-bloom-filters)
