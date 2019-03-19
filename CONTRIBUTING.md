# Contributing

The HM coding standards represent the best practices for enabling our engineering teams to work together. As the way we work evolves over time, our coding standards likewise need to evolve.


## Guidelines for Rule Changes

Bugfixes are always welcomed and can be released in minor or patch versions.

New rules or major changes to rules need to be carefully considered and balanced against the churn they may cause. Generally, code that exists right now should continue to pass in the future unless we are **intentionally** ratcheting up rules to be stricter. These cases need to be carefully considered, as breaking production code should be avoided in most cases.

Relaxing rules can be done in minor releases, but generally should be done in major releases if it's a major change (for example, allowing different file names). Use your best judgement to decide what is a major and what is a minor change, and if in doubt, run it past @joehoyle or @rmccue.

Generally, so long as changes to rules have consensus, they are fine to be published. Any controversial rules should be widely discussed, and if a tie-breaker is needed, @joehoyle can make a final call. If you're not sure, ask @rmccue. Non-controversial changes or bugfixes do not need input from @joehoyle or @rmccue provided versioning and release processes are all followed.


## Releasing

Any changes which cause existing, working production code to fail should trigger a new major release. Only bugfixes or making rules more lenient should be in minor releases.

When publishing major releases, these need to be published in a two-step process. First, publish the standards, then bump the defaults after some time. This gives projects time to assess the changes and migrate at their own pace. The time between the publish and the default bump depends on the size and scope of the major changes, but generally should be 2-4 sprints worth of time for major changes.

The process for releasing is:

* Ensure your working directory is clean and up-to-date on `master`
* Run `yarn publish` and enter the tag number
	* This publishes to npm, commits the version change, and creates a corresponding git tag
* Push the version bump commit to `master`: `git push`
* Push the new tag: `git push --tags`
	* This triggers Packagist to release the new version
* Run `./publish.sh` to push the standards for hm-linter
	* This will ask if you want to bump the latest version to the new version. Only do this for minor releases.
* For major releases, publish a changelog to the Dev H2 (significant bugfixes may also warrant a post)

If you're releasing a major version, you should also create a branch for the major version so that bugfix releases can be created. This branch should be a humanised name of the version; e.g. 0.4 would be `oh-dot-four`, 1.6 would be `one-dot-six`.
