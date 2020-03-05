################################################################################
# Automatically-generated file. Do not edit!
################################################################################

# Add inputs and outputs from these tool invocations to the build variables 
CPP_SRCS += \
../bauplan.cpp \
../db.cpp \
../einheiten.cpp \
../kampf.cpp \
../kampfbericht.cpp \
../main.cpp \
../zufall.cpp 

OBJS += \
./bauplan.o \
./db.o \
./einheiten.o \
./kampf.o \
./kampfbericht.o \
./main.o \
./zufall.o 

CPP_DEPS += \
./bauplan.d \
./db.d \
./einheiten.d \
./kampf.d \
./kampfbericht.d \
./main.d \
./zufall.d 


# Each subdirectory must supply rules for building sources it contributes
%.o: ../%.cpp
	@echo 'Building file: $<'
	@echo 'Invoking: GCC C++ Compiler'
	g++ -I/usr/include/mysql -O0 -g3 -Wall -c -fmessage-length=0 -MMD -MP -MF"$(@:%.o=%.d)" -MT"$(@)" -o "$@" "$<"
	@echo 'Finished building: $<'
	@echo ' '


