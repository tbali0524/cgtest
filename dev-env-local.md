# How to install the programming languages locally

* _Work in Progress_

To use `cgtest` you need to setup a local developer environment for the languages.
No IDE is needed only the compilers/interpreters.

## Windows

The following methods are valid for __Windows 11__.
The recommended method is to use
[winget](https://learn.microsoft.com/en-us/windows/package-manager/)
when possible, which makes updating later much easier.

Version numbers are current as of `2025.05.01`.

### Bash

* Install as part of [MSYS2](https://www.msys2.org/)
* Version: `GNU bash, version 5.2.37(2)-release (x86_64-pc-cygwin)`
* `cgtest` currently not working for bash puzzles on Windows.

### C

* Install as part of [MSYS2](https://www.msys2.org/)
* Version: `gcc.exe (Rev1, Built by MSYS2 project) 15.1.0`
* Default path: `C:\msys64\ucrt64\bin\gcc.exe`

```sh
gcc --version
```

### C\#

* Homepage: <https://dotnet.microsoft.com/en-us/>
* Version: `8.0.408` (LTS)
* Default path: `C:\Program Files\dotnet\dotnet.exe`

```sh
winget search Microsoft.DotNet.SDK
winget install Microsoft.DotNet.SDK.8
dotnet --version
```

### C++

* Install as part of [MSYS2](https://www.msys2.org/)
* Version: `g++.exe (Rev1, Built by MSYS2 project) 15.1.0`
* Default path: `C:\msys64\ucrt64\bin\g++.exe`

```sh
g++ --version
```

### Clojure

* Use [Babashka](https://babashka.org/)
* Download `bb.exe` binary from [GitHub](https://github.com/babashka/babashka/releases) & add to path
* Version: `babashka v1.12.200`

```sh
bb --version
```

### D

* Homepage: <https://babashka.org/>
* Version: `DMD64 D Compiler v2.111.0`
* Default path: `C:\D\dmd2\windows\bin64\dmd.exe`

```sh
winget search Dlang.DMD
winget install Dlang.DMD
dmd --version
```

### Dart

* Homepage: <https://dart.dev/>
* Version: `Dart SDK version: 3.7.3 (stable)`
* Default path: `C:\dart-sdk\bin\dart.exe`

```sh
winget search Google.DartSDK
winget install Google.DartSDK
dart --version
```

### F\#

### Go

### Groovy

### Haskell

### Java

### Javascript

### Kotlin

### Lua

### Objective-C

### OCaml

### Pascal

### Perl

### PHP

### Ruby

### Scala

### Swift

### Typescript

### VB.NET

---

## Linux

The following methods are valid for __Ubuntu Linux__ `24.04` running on __WSL__.
Other distributions might need different methods.
Version numbers are current as of `2025.05.01`.

### Bash

Already included in default installation.

* Version: `GNU bash, version 5.2.21(1)-release (x86_64-pc-linux-gnu)`

### C

### C\#

### C++

### Clojure

### D

### Dart

### F\#

### Go

### Groovy

### Haskell

### Java

### Javascript

### Kotlin

### Lua

### Objective-C

### OCaml

### Pascal

### Perl

Already included in default installation.

* Version: `This is perl 5, version 38, subversion 2 (v5.38.2) built for x86_64-linux-gnu-thread-multi`

### PHP

### Ruby

### Scala

### Swift

### Typescript

### VB.NET
