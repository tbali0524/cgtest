# How to install local development environments for various programming languages

To use `cgtest`, you need to setup a local development environment for the languages you want to use.
No IDE is needed, only the compilers/interpreters, to be invoked via CLI.

Instructions for [Windows](#windows) or [Linux](#linux)

Last updated: _2025.05.09_

Version numbers are current as of _2025.05.09_.

## Windows

The following methods are valid for __Windows 11__.
The recommended method is to use
[winget](https://learn.microsoft.com/en-us/windows/package-manager/)
whenever possible, which makes updating the languages much easier later.

### Bash

* Install as part of [MSYS2](https://www.msys2.org/)
* MSYS2 installer version: `msys2-x86_64-20250221.exe`
* Version: `GNU bash, version 5.2.37(2)-release (x86_64-pc-cygwin)`
* Default path and startup command: `C:/msys64/msys2_shell.cmd -defterm -here -no-start -ucrt64`
* `cgtest` currently does not work for `bash` puzzles on Windows.

### C

#### Using GCC

* Homepage: <https://gcc.gnu.org/>
* Install [MSYS2](https://www.msys2.org/)
* Install as a package with [pacman](https://wiki.archlinux.org/title/Pacman)
* Version: `gcc.exe (Rev1, Built by MSYS2 project) 15.1.0`
* Default path: `C:\msys64\ucrt64\bin\gcc.exe`
* Update with `pacman`

```pwsh
pacman -S mingw-w64-ucrt-x86_64-gcc
gcc --version
# list all installed MSYS2 packages:
pacman -Q
# updating all MSYS2 packages:
pacman -Suy
# package maintenance for MSYS2:
paccache -r
pacman -Qm
pacman-key --refresh-keys
pacman -Sy msys2-keyring; pacman -Suy
```

#### Using Clang

* Homepage: <https://clang.llvm.org/>
* Version: `clang version 20.1.4`
* Default path: `C:\Program Files\LLVM\bin\clang.exe`

```pwsh
winget search LLVM.LLVM
winget install LLVM.LLVM
clang --version
winget update LLVM.LLVM
```

### C\#

* Homepage: <https://dotnet.microsoft.com/en-us/>
* Install as part of __.NET__
* Version: `dotnet 8.0.408` (LTS)
* Default path: `C:\Program Files\dotnet\dotnet.exe`

```pwsh
winget search Microsoft.DotNet.SDK
winget install Microsoft.DotNet.SDK.8
dotnet --version
winget update Microsoft.DotNet.SDK.8
```

### C++

#### Using GCC

* See section __C__.
* Version: `g++.exe (Rev1, Built by MSYS2 project) 15.1.0`
* Default path: `C:\msys64\ucrt64\bin\g++.exe`

```pwsh
g++ --version
```

#### Using Clang

* See section __C__.

### Clojure

* Homepage: <https://clojure.org/>
* Use with __Babashka__: <https://babashka.org/>
* Download `bb.exe` binary from [GitHub](https://github.com/babashka/babashka/releases), add to path
* Version: `babashka v1.12.200`
* Recommended path: `c:\tools\cli\bb.exe`
* Update manually

```pwsh
mkdir c:\tools\cli
cd c:\tools\cli
curl -OL https://github.com/babashka/babashka/releases/download/v1.12.200/babashka-1.12.200-windows-amd64.zip
unzip babashka-1.12.200-windows-amd64.zip
del babashka-1.12.200-windows-amd64.zip 
bb --version
```

### D

* Homepage: <https://dlang.org/>
* Version: `DMD64 D Compiler v2.111.0`
* Default path: `C:\D\dmd2\windows\bin64\dmd.exe`

```pwsh
winget search Dlang.DMD
winget install Dlang.DMD
dmd --version
winget update Dlang.DMD
```

### Dart

* Homepage: <https://dart.dev/>
* Version: `Dart SDK version: 3.7.3 (stable)`
* Default path: `C:\dart-sdk\bin\dart.exe`

```pwsh
winget search Google.DartSDK
winget install Google.DartSDK
dart --version
winget update Google.DartSDK
```

### F\#

* Install as part of __.NET__
* See section __C\#__.

```pwsh
dotnet --version
dotnet fsi --version
```

### Go

* Homepage: <https://go.dev/>
* Version: `go version go1.24.3 windows/amd64`
* Default path: `C:\Program Files\Go\bin\go.exe`

```pwsh
winget search GoLang
winget install GoLang.Go
go version
winget update GoLang.Go
```

### Groovy

* Homepage: <https://groovy-lang.org/>
* Version: `Groovy Version: 4.0.26 JVM: 21.0.7 Vendor: Eclipse Adoptium OS: Windows 11`
* Default path: `C:\Program Files (x86)\Groovy\bin\groovy.bat`
* Requires Java JDK installed.

```pwsh
winget search groovy
winget install Apache.Groovy.4
groovy --version
winget update Apache.Groovy.4
```

### Haskell

* Homepage: <https://www.haskell.org/>
* Install with [GHCup](https://www.haskell.org/ghcup/)
* Version: `The Glorious Glasgow Haskell Compilation System, version 9.12.2`
* Default path: `C:\ghcup\bin\ghc.exe`
* Default packages path: `C:\cabal\packages`
* Update manually with: `ghcup tui`

```pwsh
Set-ExecutionPolicy Bypass -Scope Process -Force;[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; try { & ([ScriptBlock]::Create((Invoke-WebRequest https://www.haskell.org/ghcup/sh/bootstrap-haskell.ps1 -UseBasicParsing))) -Interactive -DisableCurl } catch { Write-Error $_ }
ghc --version
ghcup tui
```

### Java

* Homepage: <https://openjdk.org/>, <https://www.java.com/>
* Recommended distribution: [Eclipse Adoptium](https://adoptium.net/)
* Version: `OpenJDK Runtime Environment Temurin-21.0.7+6 (build 21.0.7+6-LTS)`
* Default path: `C:\Program Files\Eclipse Adoptium\jdk-21.0.7.6-hotspot\bin\java.exe`
* Set environment variable: `JAVA_HOME`

```pwsh
winget search groovy
winget install EclipseAdoptium.Temurin.21.JDK
java --version
sudo setx JAVA_HOME "C:\Program Files\Eclipse Adoptium\jdk-21.0.7.6-hotspot\" /m
echo $Env:JAVA_HOME
winget update EclipseAdoptium.Temurin.21.JDK
```

### Javascript

* Homepage: <https://javascript.info/>
* Recommended runtime: [Node.js](https://nodejs.org/)
* Version: `v22.15.0` (LTS)
* Default path: `C:\Program Files\nodejs\node.exe`

```pwsh
winget search OpenJS.NodeJS
winget install OpenJS.NodeJS.LTS 
node -v
npm -v
winget update OpenJS.NodeJS.LTS 
```

### Kotlin

* Homepage: <https://kotlinlang.org/>
* Download compiler from [GitHub](https://github.com/JetBrains/kotlin/releases/latest/)
* Version: `Kotlin version 2.1.20-release-217 (JRE 21.0.7+6-LTS)`
* Recommended path: `C:\tools\kotlinc\bin\kotlinc.bat`
* Requires Java JDK installed.
* Update manually

```pwsh
mkdir c:\tools\kotlinc
cd c:\tools\kotlinc
curl -OL https://github.com/JetBrains/kotlin/releases/download/v2.1.20/kotlin-compiler-2.1.20.zip
unzip https://github.com/JetBrains/kotlin/releases/download/v2.1.20/kotlin-compiler-2.1.20.zip
kotlinc -version
```

### Lua

* Homepage: <https://www.lua.org/>
* Version: `Lua 5.4.6  Copyright (C) 1994-2023 Lua.org, PUC-Rio`
* Default path: `C:\Users\${Env:Username}\AppData\Local\Programs\Lua\bin\lua.exe`

```pwsh
winget search DEVCOM.Lua
winget install DEVCOM.Lua 
lua -v
winget update DEVCOM.Lua 
```

### Objective-C

* `cgtest` currently does not work for `Objective-C` puzzles on Windows.

### OCaml

* Homepage: <https://ocaml.org/>
* Version: `The OCaml native-code compiler, version 5.3.0`
* Recommended path: `C:\tools\FPC\bin\i386-Win32\fpc.exe`
* Default path: `C:\Users\${Env:Username}\AppData\Local\opam\default\bin\ocamlopt.exe`
* Requires git

```pwsh
winget install Git.Git OCaml.opam
opam init -y
(& opam env) -split '\r?\n' | ForEach-Object { Invoke-Expression $_ }
ocamlopt --version
```

### Pascal

* Homepage: <https://www.freepascal.org/>
* Install Windows 32-bit native compiler + Windows 64-bit cross-compiler
* Version: `Free Pascal Compiler version 3.2.2 [2021/05/15] for i386`
* Recommended path: `C:\tools\FPC\bin\i386-Win32\fpc.exe`
* Update manually

```pwsh
fpc -iW
```

### Perl

* Homepage: <https://www.perl.org/>
* Prefered: install StrawberryPerl with `winget`
* Also available: install as part of XAMPP (outdated): `winget search ApacheFriends.Xampp.8.2`
* Version: `This is perl 5, version 32, subversion 1 (v5.32.1) built for MSWin32-x64-multi-thread`
* Default path (if using XAMPP): `C:\xampp\perl\bin\perl.exe`

```pwsh
winget search perl
winget install StrawberryPerl.StrawberryPerl  
perl --version
winget update StrawberryPerl.StrawberryPerl
```

### PHP

* Homepage: <https://windows.php.net/download/>
* Use `VS17 x64 Thread Safe` if using with Apache, `NTS` is also ok for CLI.
* Install with `winget`
* Also available: install as part of XAMPP (outdated): `winget search ApacheFriends.Xampp.8.2`
* Install extensions with [PIE](https://github.com/php/pie) or install [XDebug](https://xdebug.org/) manually
* Keep separate configs for "JIT enabled" and "XDebug enabled" runs.
* Version: `PHP 8.4.7 (cli) (built: May  6 2025 14:12:45) (ZTS Visual C++ 2022 x64)`
* Default path (if using XAMPP): `c:\xampp\php\php.exe`

```pwsh
winget search PHP.PHP
winget install PHP.PHP.8.4
php --version
winget update PHP.PHP.8.4
```

### Python

* Homepage: <https://www.python.org/>
* Version: `Python 3.13.3`
* Default path: `C:\Users\${Env:Username}\AppData\Local\Programs\Python\Python313\python.exe`

```pwsh
winget search Python.Python
winget install Python.Python.3.13  
python --version
winget update Python.Python.3.13
```

### Ruby

* Homepage: <https://www.ruby-lang.org/>
* Version: `ruby 3.4.3 (2025-04-14 revision d0b7e5b6a0) +PRISM [x64-mingw-ucrt]`
* Default path: `C:\Ruby34-x64\bin\ruby.exe`

```pwsh
winget search RubyInstallerTeam.Ruby
winget install RubyInstallerTeam.RubyWithDevKit.3.4  
ruby --version
winget update RubyInstallerTeam.RubyWithDevKit.3.4
```

### Rust

* Homepage: <https://www.rust-lang.org/>
* Install with the `rustup-init (64-bit)`, update with `rustup`
* Also available: Installing with `winget`: `winget search Rustlang.Rust`
* Version: `rustc 1.86.0 (05f9846f8 2025-03-31)`
* Toolchain: `stable-x86_64-pc-windows-msvc`
* Recommended path (after moving to a DevDrive): `D:\packages\cargo\bin\rustc.exe`
* Requires: [Microsoft C++ Build Tools](https://visualstudio.microsoft.com/visual-cpp-build-tools/)
    * Use `Visual Studio Installer`, install `Visual Studio Build Tools 2022`

```pwsh
# info
rustup --version
cargo --version
rustup show
# updating
rustup self update
rustup update stable
```

### Scala

* Homepage: <https://www.scala-lang.org/>
* Version: `ruby 3.4.3 (2025-04-14 revision d0b7e5b6a0) +PRISM [x64-mingw-ucrt]`
* Default path: `C:\Users\{$Env:Username}\AppData\Local\Coursier\data\bin\scala.bat`
* Update manually

```pwsh
scala --version
scala-cli --version
```

### Swift

* `cgtest` currently does not work for `Swift` puzzles on Windows.

### Typescript

* Homepage: <https://www.typescriptlang.org/>
* See section __Javascript__.
* Version: `tsc v5.8.3`

```pwsh
npm install -g typescript
node -v
tsc -v
npm update -g
```

### VB.NET

* Install as part of __.NET__
* See section __C\#__.

```pwsh
dotnet --version
```

### Fortran

* Homepage: <https://fortran-lang.org/>
* Install [MSYS2](https://www.msys2.org/), then install as a package
* See section __C__.
* Version: `GNU Fortran (Rev1, Built by MSYS2 project) 15.1.0`
* Default path: `C:\msys64\ucrt64\bin\gfortran.exe`

```pwsh
pacman -S mingw-w64-ucrt-x86_64-gcc-fortran
gfortran --version
```

---

## Linux

The following methods are valid for __Ubuntu Linux__ `24.04` running on __WSL__.
Other distributions might need different methods.

### Bash

* Already included in the default installation.
* Version: `GNU bash, version 5.2.21(1)-release (x86_64-pc-linux-gnu)`

```sh
bash --version
# update all packages
sudo apt update
sudo apt upgrade
```

### C

#### Using GCC

* Version: `gcc (Ubuntu 13.3.0-6ubuntu2~24.04) 13.3.0`

```sh
sudo apt update && sudo apt install -y build-essential
gcc --version
```

#### Using Clang

* Version: `Ubuntu clang version 19.1.7 (++20250114103332+cd708029e0b2-1~exp1~20250114103446.78)`

```sh
bash -c "$(wget -O - https://apt.llvm.org/llvm.sh)"
clang-19 --version
```

### C\#

* Install as part of __.NET__
* Version: `dotnet 8.0.115`

```sh
apt list dotnet-sdk-8.0
sudo apt update && sudo apt install -y dotnet-sdk-8.0
dotnet --version
```

### C++

* See Section __C__
* Version: `g++ (Ubuntu 13.3.0-6ubuntu2~24.04) 13.3.0`

```sh
g++ --version
```

### Clojure

* Version: `babashka v1.12.200`

```sh
curl -sLO https://raw.githubusercontent.com/babashka/babashka/master/install
chmod +x install
sudo ./install
bb --version
```

### D

* install & update manually (in home dir)
* Version: `DMD64 D Compiler v2.111.0`

```sh
curl -fsS https://dlang.org/install.sh | bash -s dmd
# add to .bashrc
source "/home/$USER/dlang/dmd-2.111.0/activate"
dmd --version
~/dlang/install.sh update
```

### Dart

* install & update manually
* Version: `Dart SDK version: 3.7.3 (stable) (None) on "linux_x64"`

```sh
sudo apt-get update && sudo apt-get install apt-transport-https
wget -qO- https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo gpg --dearmor -o /usr/share/keyrings/dart.gpg
echo 'deb [signed-by=/usr/share/keyrings/dart.gpg arch=amd64] https://storage.googleapis.com/download.dartlang.org/linux/debian stable main' | sudo tee /etc/apt/sources.list.d/dart_stable.list
sudo apt-get update && sudo apt-get install dart
dart --version
```

### F\#

* Install as part of __.NET__
* See section __C\#__.
* Version: `dotnet 8.0.115`

```sh
dotnet --version
```

### Go

* Version: `go version go1.22.2 linux/amd64`

```sh
apt list golang
sudo apt update && sudo apt install -y golang
go version
```

### Groovy

* Default Groovy in Ubuntu 24.04 is outdated v2.4: `apt list groovy`
* Install with [SDKMan!](https://sdkman.io/)
* Version: `Groovy Version: 4.0.26 JVM: 21.0.6 Vendor: Ubuntu OS: Linux`

```sh
curl -s "https://get.sdkman.io" | bash
source "/home/$USER/.sdkman/bin/sdkman-init.sh"
sdk version
groovy --version
sdk selfupdate
sdk upgrade groovy
```

### Haskell

* Default Haskell in Ubuntu 24.04 is outdated v9.4: `apt list haskell`
* Install with [GHCup](https://www.haskell.org/ghcup/)
* Version: `The Glorious Glasgow Haskell Compilation System, version 9.6.7`

```sh
sudo apt install -y build-essential curl libffi-dev libffi8 libgmp-dev libgmp10 libncurses-dev pkg-config
curl --proto '=https' --tlsv1.2 -sSf https://get-ghcup.haskell.org | sh
. /home/tbali/.ghcup/env
ghc --version
ghcup tui
```

### Java

* Version: `OpenJDK Runtime Environment (build 21.0.6+7-Ubuntu-124.04.1)`

```sh
apt search openjdk-21
sudo apt update && sudo apt install -y openjdk-21-jdk
java --version
```

### Javascript

* Default Nodejs in Ubuntu 24.04 is outdated v18: `apt list nodejs`
* Version: `node.js v22.15.0`

```sh
curl -o- https://fnm.vercel.app/install | bash
source ~/.bashrc
fnm --version
fnm install 22
node -v
npm -v
corepack enable yarn
yarn -v
corepack enable pnpm
pnpm -v
```

### Kotlin

* Install with [SDKMan!](https://sdkman.io/)
* See Section __Groovy__
* Version: `kotlinc-jvm 2.1.20 (JRE 21.0.6+7-Ubuntu-124.04.1)`

```sh
sdk list kotlin
sdk install kotlin
kotlinc -version
sdk upgrade kotlin
```

### Lua

* Version: `Lua 5.4.6  Copyright (C) 1994-2023 Lua.org, PUC-Rio`

```sh
apt list lua5.4
sudo apt update && sudo apt install -y lua5.4
lua -v
```

### Objective-C

* `cgtest` currently does not work for `Objective-C` puzzles on Linux.

### OCaml

* Version: `OCaml 4.14.1`

```sh
apt list ocaml
sudo apt update && sudo apt install -y ocaml
ocamlopt --version
```

### Pascal

* Version: `Free Pascal Compiler 3.2.2+dfsg-32`

```sh
apt list fpc
sudo apt update && sudo apt install -y fpc
fpc -iW
```

### Perl

* Already included in the default installation.
* Version: `This is perl 5, version 38, subversion 2 (v5.38.2) built for x86_64-linux-gnu-thread-multi`

```sh
perl --version
```

### PHP

* Already included in the default installation.
* Version: `PHP 8.3.21 (cli) (built: May  9 2025 06:27:43) (NTS)`

```sh
php --version
```

### Python

* Already included in the default installation.
* Version: `Python 3.12.3`

```sh
python --version
```

### Ruby

* Default ruby in Ubuntu 24.04 is outdated v1: `apt list ruby`
* Install with [rbenv](https://rbenv.org/) using [this help](https://docs.vultr.com/how-to-install-ruby-on-ubuntu-24-04)
* Version: `ruby 3.4.3 (2025-04-14 revision d0b7e5b6a0) +PRISM [x86_64-linux]`

```sh
sudo apt install -y autoconf patch build-essential rustc libssl-dev libyaml-dev libreadline6-dev zlib1g-dev libgmp-dev libncurses5-dev libffi-dev libgdbm6 libgdbm-dev libdb-dev libtool uuid-dev
curl -fsSL https://github.com/rbenv/rbenv-installer/raw/HEAD/bin/rbenv-installer | bash
# echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
rbenv -v
rbenv install -l
rbenv install 3.4.3
rbenv global 3.4.3
ruby --version
```

### Rust

* Install with [rustup](https://www.rust-lang.org/tools/install)
* Version: `ruby 3.4.3 (2025-04-14 revision d0b7e5b6a0) +PRISM [x86_64-linux]`

```sh
curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh
. "$HOME/.cargo/env"
cargo --version
rustup self update
rustup update stable
```

### Scala

* Default Scala in Ubuntu 24.04 is outdated v2.11: `apt list scala`
* Install with [SDKMan!](https://sdkman.io/)
* See Section __Groovy__
* Version: `Scala version (default): 3.6.4`

```sh
sdk list scala
sdk install scala
scala --version
sdk upgrade scala
```

### Swift

* `cgtest` currently does not work for `Swift` puzzles on Linux.

### Typescript

* See section __Javascript__.

```sh
npm install -g typescript
node -v
tsc -v
npm update -g
```

### VB.NET

* Install as part of __.NET__
* See section __C\#__.
* Version: `dotnet 8.0.115`

```sh
dotnet --version
```

### Fortran

* Version: `GNU Fortran (Ubuntu 13.3.0-6ubuntu2~24.04) 13.3.0`
* Requires `gcc`

```pwsh
apt list gfortran
sudo apt update && sudo apt install -y gfortran
gfortran --version
```
